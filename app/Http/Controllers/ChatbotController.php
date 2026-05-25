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
            return response()->json(['status' => 'error', 'message' => 'Pesan tidak boleh kosong.'], 400);
        }

        try {
            $hfToken = env('HF_TOKEN');

            // 1. Tarik semua produk yang search_context-nya TIDAK kosong
            $products = Product::whereNotNull('search_context')
                               ->where('search_context', '!=', '')
                               ->get();

            $topProducts = collect();

            // Jika database ternyata kosong, kita langsung pakai fallback di bawah
            if ($products->count() > 0) {
                $sentences = $products->pluck('search_context')->toArray();

                try {
                    // Bungkus payload sesuai format curl pipeline kamu
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
                        
                        // Pastikan respons dari HF berbentuk array score angka
                        if (is_array($scores)) {
                            foreach ($products as $index => $product) {
                                $product->similarity_score = $scores[$index] ?? 0;
                            }
                            // Ambil 3 produk dengan kecocokan tertinggi
                            $topProducts = $products->sortByDesc('similarity_score')->take(3);
                        }
                    } else {
                        Log::warning('HF Pipeline gagal. Response: ' . $similarityResponse->body());
                    }
                } catch (\Exception $e) {
                    Log::error('Gagal hit HF Similarity: ' . $e->getMessage());
                }

                // [FALLBACK SYSTEM] Jika HF bermasalah/tidak merespon, ambil 3 produk pertama secara acak/terbaru
                if ($topProducts->isEmpty()) {
                    $topProducts = $products->take(10);
                }
            }

            // 2. Bangun teks katalog untuk disuntikkan ke Llama
            if ($topProducts->count() > 0) {
                $contextText = "Berikut adalah daftar produk Parfum yang tersedia di toko Scentify saat ini:\n";
                foreach ($topProducts as $index => $product) {
                    $contextText .= ($index + 1) . ". Nama Parfum: {$product->name}, Kategori: {$product->category}, Gender: {$product->gender_type}, Deskripsi Aroma: {$product->description}\n";
                }
            } else {
                // Kondisi jika database benar-benar kosong total dari Tinker kemarin
                $contextText = "PERINGATAN: Saat ini katalog produk di database sedang kosong karena maintenance. Beritahu pelanggan dengan sopan bahwa sistem produk sedang diperbarui.";
            }

            // 3. Susun Instruksi Akhir untuk Llama 3.1
            $systemPrompt = "Anda adalah Scenty, virtual assistant premium untuk toko parfum 'Scentify'.\n";
            $systemPrompt .= "Tugas utama Anda adalah merekomendasikan parfum berdasarkan data katalog di bawah ini:\n";
            $systemPrompt .= "=== KATALOG PRODUK ===\n" . $contextText . "\n====================\n\n";
            $systemPrompt .= "Aturan Chat:\n";
            $systemPrompt .= "- Jawablah menggunakan Bahasa Indonesia yang anggun, ramah, dan mewah.\n";
            $systemPrompt .= "- Jangan sebutkan urusan teknis seperti kata 'database', 'katalog', atau 'fail-safe' kepada pelanggan.\n";
            $systemPrompt .= "- Jika produk yang dicari pelanggan ada di dalam teks katalog di atas, tawarkan produk tersebut dengan persuasif.";

            // 4. Kirim ke Llama 3.1
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
                $reply = $data['choices'][0]['message']['content'] ?? 'Maaf, saya kehilangan sinyal pikiran. Bisa diulangi?';
                
                return response()->json([
                    'status' => 'success',
                    'reply'  => $reply
                ]);
            }

            return response()->json(['status' => 'error', 'message' => 'Llama API Error: ' . $llamaResponse->body()], 500);

        } catch (\Exception $e) {
            Log::error('Chatbot Controller Error Ultimate: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'DEBUG ERROR: ' . $e->getMessage() . ' di baris ' . $e->getLine()
            ], 500);
        }
    }
}