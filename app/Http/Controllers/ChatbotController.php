<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $userMessage = $request->input('message');

        if (!$userMessage) {
            return response()->json(['status' => 'error', 'message' => 'Pesan kosong.'], 400);
        }

        try {
            $hfToken = env('HF_TOKEN');

            // 1. Deteksi Data User
            $currentUser = auth()->user();
            if ($currentUser) {
                $userInfoContext = "Pelanggan yang sedang berbicara dengan Anda bernama: {$currentUser->name} (Email: {$currentUser->email}).";
            } else {
                $userInfoContext = "Pelanggan saat ini belum login (berstatus sebagai Guest/Tamu). Panggil dia dengan sebutan 'Kakak' atau 'Scent Lover'.";
            }

            // 2. Tarik produk BERSERTA relasi varian dan brand
            $products = Product::with(['variants', 'brand', 'notes'])->get();

            // Ensure every product has a usable in-memory search_context (do not force-save)
            foreach ($products as $p) {
                if (empty($p->search_context)) {
                    // Build a lightweight context from available fields
                    $brandName = $p->brand ? $p->brand->name : 'Scentify';
                    $variantParts = [];
                    if ($p->variants && $p->variants->count() > 0) {
                        foreach ($p->variants as $v) {
                            $variantParts[] = "{$v->size}ml priced Rp " . number_format($v->price, 0, ',', '.');
                        }
                    }
                    $notes = $p->notes ? $p->notes->pluck('name')->toArray() : [];
                    $notesStr = $notes ? 'Notes: ' . implode(', ', $notes) . '.' : '';
                    $variantsStr = count($variantParts) ? ' Varian: ' . implode(', ', $variantParts) . '.' : '';
                    $p->search_context = "Produk: {$p->name}. Brand: {$brandName}. Kategori: {$p->category}. Deskripsi: {$p->description}. {$notesStr}{$variantsStr}";
                }
            }

            $topProducts = collect();

            if ($products->count() > 0) {

                // =======================================================
                // HYBRID SEARCH TAHAP 1: EXACT KEYWORD MATCH (Pencarian Nama)
                // =======================================================
                $msgLower = strtolower($userMessage);
                $keywordMatches = $products->filter(function ($p) use ($msgLower) {
                    $brandName = $p->brand ? strtolower($p->brand->name) : '';
                    $productName = strtolower($p->name);

                    // Jika user menyebut nama brand atau nama produk di chatnya, langsung tangkap!
                    return ($brandName && str_contains($msgLower, $brandName)) ||
                        str_contains($msgLower, $productName);
                });

                // =======================================================
                // HYBRID SEARCH TAHAP 2: VECTOR SIMILARITY (Pencarian Makna)
                // =======================================================
                $sentences = $products->pluck('search_context')->toArray();
                $vectorMatches = collect();

                try {
                    $similarityResponse = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $hfToken,
                        'Content-Type'  => 'application/json',
                    ])->timeout(15)->post('https://router.huggingface.co/hf-inference/models/sentence-transformers/all-MiniLM-L6-v2/pipeline/sentence-similarity', [
                        'inputs' => [
                            'source_sentence' => $userMessage,
                            'sentences'       => $sentences
                        ]
                    ]);

                    if ($similarityResponse->successful()) {
                        $scores = $similarityResponse->json();
                        if (is_array($scores)) {
                            foreach ($products as $index => $product) {
                                $product->similarity_score = $scores[$index] ?? 0;
                            }
                            // Ambil 10 produk yang maknanya paling relevan
                            $vectorMatches = $products->sortByDesc('similarity_score')->take(10);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Koneksi HF Similarity bermasalah: ' . $e->getMessage());
                }

                // =======================================================
                // GABUNGKAN HASIL TAHAP 1 & TAHAP 2
                // =======================================================
                // Produk dari keyword mendapat prioritas utama, lalu digabung dengan hasil vektor
                $topProducts = $keywordMatches->merge($vectorMatches)->unique('id')->take(5);

                if ($topProducts->isEmpty()) {
                    // Fallback jika API mati & tidak ada keyword yang cocok
                    $topProducts = $products->sortByDesc('created_at')->take(5);
                }
            }

            // 3. Susun teks katalog final
            $contextText = "";
            foreach ($topProducts as $index => $product) {
                $brandName = $product->brand ? $product->brand->name : 'Scentify';
                $variantInfo = "";

                if ($product->variants && $product->variants->count() > 0) {
                    $vDetails = [];
                    foreach ($product->variants as $v) {
                        $priceFmt = 'Rp ' . number_format($v->price, 0, ',', '.');
                        $vDetails[] = "{$v->size}ml seharga {$priceFmt}";
                    }
                    $variantInfo = " Varian: " . implode(", ", $vDetails) . ".";
                }

                // Tambahkan penekanan Brand agar AI lebih mudah membaca
                $contextText .= "- [Brand: {$brandName}] {$product->search_context}{$variantInfo}\n";
            }

            // 4. Susun System Prompt
            $systemPrompt = "Anda adalah Scenty, asisten AI mewah dan cerdas untuk butik parfum 'Scentify'.\n";
            $systemPrompt .= "=== INFORMASI USER ===\n" . $userInfoContext . "\n======================\n\n";
            $systemPrompt .= "=== KATALOG PRODUK RELEVAN ===\n" . $contextText . "\n==============================\n\n";
            $systemPrompt .= "Aturan Komunikasi:\n";
            $systemPrompt .= "1. Selalu sapa user secara personal memanfaatkan data INFORMASI USER.\n";
            $systemPrompt .= "2. Jika user bertanya tentang brand tertentu (misal 'Afnan'), sebutkan produk dari brand tersebut berdasarkan data KATALOG PRODUK RELEVAN.\n";
            $systemPrompt .= "3. Berikan informasi harga atau ukuran varian secara akurat sesuai katalog.\n";
            $systemPrompt .= "4. Jawab dengan Bahasa Indonesia yang elegan, profesional, penuh sopan santun khas butik mewah.";

            // 5. Kirim ke Llama 3.1
            $llamaResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $hfToken,
                'Content-Type'  => 'application/json',
            ])->timeout(30)->post('https://router.huggingface.co/v1/chat/completions', [
                'model' => 'meta-llama/Llama-3.1-8B-Instruct:novita',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage]
                ],
                'stream' => false,
            ]);

            if ($llamaResponse->successful()) {
                $data = $llamaResponse->json();
                $reply = $data['choices'][0]['message']['content'] ?? 'Maaf Kak, racikan pikiran saya terganggu. Bisa diulangi?';

                return response()->json([
                    'status' => 'success',
                    'reply'  => $reply
                ]);
            }

            Log::error('Gagal memanggil Llama: ' . $llamaResponse->body());
            return response()->json(['status' => 'error', 'message' => 'Gagal terhubung ke AI.'], 500);
        } catch (\Exception $e) {
            Log::error('Ultimate Chatbot Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Terjadi kendala pada sistem internal.'], 500);
        }
    }
}
