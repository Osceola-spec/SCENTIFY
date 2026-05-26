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

            // 1. Deteksi Data User yang sedang Login (Mekanisme Auth Laravel)
            $currentUser = auth()->user();
            if ($currentUser) {
                $userInfoContext = "Pelanggan yang sedang berbicara dengan Anda bernama: {$currentUser->name} (Email: {$currentUser->email}).";
            } else {
                $userInfoContext = "Pelanggan saat ini belum login (berstatus sebagai Guest/Tamu). Panggil dia dengan sebutan 'Kakak' atau 'Scent Lover'.";
            }

            // 2. Tarik semua produk yang search_context-nya sudah terisi mewah
            $products = Product::whereNotNull('search_context')->where('search_context', '!=', '')->get();
            $topProducts = collect();

            if ($products->count() > 0) {
                $sentences = $products->pluck('search_context')->toArray();

                try {
                    // Minta Hugging Face Pipeline Similarity mencocokkan chat user dengan konteks kaya kita
                    $innerPayload = json_encode([
                        'source_sentence' => $userMessage,
                        'sentences'       => $sentences
                    ]);

                    $similarityResponse = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $hfToken,
                        'Content-Type'  => 'application/json',
                    ])->timeout(15)->post('https://router.huggingface.co/hf-inference/models/sentence-transformers/all-MiniLM-L6-v2/pipeline/sentence-similarity', [
                        'inputs' => $innerPayload
                    ]);

                    if ($similarityResponse->successful()) {
                        $scores = $similarityResponse->json();
                        if (is_array($scores)) {
                            foreach ($products as $index => $product) {
                                $product->similarity_score = $scores[$index] ?? 0;
                            }
                            $topProducts = $products->sortByDesc('similarity_score')->take(3);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Koneksi HF Similarity bermasalah: ' . $e->getMessage());
                }

                if ($topProducts->isEmpty()) {
                    $topProducts = $products->take(10); // Fallback sistem jika API similarity down
                }
            }

            // 3. Susun teks katalog final
            $contextText = "";
            foreach ($topProducts as $index => $product) {
                $contextText .= ($index + 1) . ". {$product->search_context}\n";
            }

            // 4. Susun System Prompt (Gabungkan Persona + Info User + Data Katalog Kaya)
            $systemPrompt = "Anda adalah Scenty, asisten AI mewah dan personal untuk toko parfum 'Scentify'.\n";
            $systemPrompt .= "=== INFORMASI USER ===\n" . $userInfoContext . "\n======================\n\n";
            $systemPrompt .= "=== KATALOG PRODUK RELEVAN ===\n" . $contextText . "\n==============================\n\n";
            $systemPrompt .= "Aturan Komunikasi:\n";
            $systemPrompt .= "- Selalu sapa user secara personal memanfaatkan data INFORMASI USER di atas (contoh: 'Halo Kak Steven, ada yang bisa Scenty bantu hari ini?'). Jika guest, gunakan sapaan hangat universal.\n";
            $systemPrompt .= "- Berikan informasi harga, ukuran varian, atau scent notes secara akurat sesuai data KATALOG PRODUK RELEVAN di atas.\n";
            $systemPrompt .= "- Jawab dengan Bahasa Indonesia yang elegan, profesional, penuh sopan santun khas butik parfum mewah.";

            // 5. Kirim data super lengkap ke Llama 3.1
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

            return response()->json(['status' => 'error', 'message' => 'Gagal terhubung ke otak AI.'], 500);

        } catch (\Exception $e) {
            Log::error('Ultimate Chatbot Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Terjadi kendala pada sistem internal.'], 500);
        }
    }
}