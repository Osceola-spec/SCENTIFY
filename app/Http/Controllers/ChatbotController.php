<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500'
        ]);

        $userMessage = $request->input('message');

        try {
            // 1. Ubah pertanyaan user menjadi Vector Embedding
            $embeddingResponse = OpenAI::embeddings()->create([
                'model' => 'text-embedding-3-small',
                'input' => $userMessage,
            ]);
            $userEmbedding = $embeddingResponse->embeddings[0]->embedding;

            // 2. Cari 3 produk paling relevan di database MySQL menggunakan fungsi PHP tadi
            $matchedProducts = Product::searchByVector($userEmbedding, 3);

            // 3. Susun data produk tersebut menjadi teks konteks untuk AI
            $productContext = "";
            foreach ($matchedProducts as $product) {
                // Kita juga bisa melihat seberapa mirip produk ini (skor antara 0 sampai 1)
                // $product->similarity_score
                $productContext .= "- Nama: {$product->name}, Harga: Rp " . number_format($product->price, 0, ',', '.') . ", Deskripsi: {$product->description}\n";
            }

            // 4. Kirim pesan ke ChatGPT bersama dengan konteks produk dari database
            $aiResponse = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini', // Model yang cepat dan murah untuk chatbot
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Kamu adalah asisten belanja AI yang ramah untuk toko online Scentify. " .
                                     "Tugasmu adalah menjawab pertanyaan customer berdasarkan DATA PRODUK asli berikut ini. " .
                                     "Jangan merekomendasikan produk di luar data yang diberikan! Jika tidak ada produk yang cocok, " .
                                     "katakan dengan sopan bahwa produk belum tersedia.\n\n" .
                                     "DATA PRODUK TOKO:\n" . $productContext
                    ],
                    [
                        'role' => 'user',
                        'content' => $userMessage
                    ],
                ],
            ]);

            // 5. Kembalikan jawaban AI ke Frontend
            return response()->json([
                'status' => 'success',
                'reply' => $aiResponse->choices[0]->message->content,
                'recomended_items' => $matchedProducts->pluck('name') // Opsional, untuk nampilin card produk
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Waduh, ada kendala teknis: ' . $e->getMessage()
            ], 500);
        }
    }
}