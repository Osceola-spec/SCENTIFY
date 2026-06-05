<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Branch;
use App\Models\Review;
use App\Models\Order;

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
                // Fetch addresses for the user if they exist
                $addresses = $currentUser->addresses ? $currentUser->addresses->pluck('address_line1')->toArray() : [];
                $addressesStr = count($addresses) > 0 ? "Alamat: " . implode('; ', $addresses) : "Belum ada alamat";
                
                $userInfoContext = "Pelanggan yang sedang berbicara dengan Anda bernama: {$currentUser->username} (Nama Asli: {$currentUser->first_name} {$currentUser->last_name}, Email: {$currentUser->email}, Telepon: {$currentUser->phone}). {$addressesStr}.";
            } else {
                $userInfoContext = "Pelanggan saat ini belum login (berstatus sebagai Guest/Tamu). Panggil dia dengan sebutan 'Kakak' atau 'Scent Lover'.";
            }

            // Siapkan array untuk menampung semua knowledge base
            $allKnowledge = collect();

            // 2. Tarik produk BERSERTA relasi varian dan brand
            $products = Product::with(['variants', 'brand', 'notes'])->get();
            foreach ($products as $p) {
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
                
                $allKnowledge->push([
                    'type' => 'Product',
                    'id' => $p->id,
                    'search_context' => "Produk Parfum: {$p->name}. Brand: {$brandName}. Kategori: {$p->category}. Deskripsi: {$p->description}. {$notesStr}{$variantsStr}",
                    'item' => $p
                ]);
            }

            // 3. Tarik Cabang Toko
            $branches = Branch::where('is_active', true)->get();
            foreach ($branches as $b) {
                $cityInfo = $b->city ? ", Kota: {$b->city}" : "";
                $allKnowledge->push([
                    'type' => 'Branch',
                    'id' => $b->id,
                    'search_context' => "Cabang Toko Scentify: {$b->name}. Alamat: {$b->address}{$cityInfo}. Jam Operasional: {$b->opening_hours}. Telepon: {$b->phone}. Email: {$b->email}.",
                    'item' => $b
                ]);
            }

            // 4. Tarik Reviews (Semua Review)
            $reviews = Review::with(['product', 'user'])->get();
            foreach ($reviews as $r) {
                $prodName = $r->product ? $r->product->name : 'Produk tidak diketahui';
                $reviewer = $r->user ? $r->user->username : 'Anonim';
                $allKnowledge->push([
                    'type' => 'Review',
                    'id' => $r->id,
                    'search_context' => "Review Produk: {$prodName}. Rating: {$r->rating}/5. Ulasan oleh {$reviewer}: '{$r->comment}'. Judul: {$r->title}.",
                    'item' => $r
                ]);
            }

            // 5. Tarik Orders (Hanya untuk user yang login)
            if ($currentUser) {
                $orders = Order::with(['items.variant.product'])->where('user_id', $currentUser->id)->get();
                foreach ($orders as $o) {
                    $itemNames = [];
                    foreach ($o->items as $item) {
                        $pName = $item->variant && $item->variant->product ? $item->variant->product->name : 'Item';
                        $itemNames[] = "{$item->quantity}x {$pName}";
                    }
                    $itemsStr = implode(', ', $itemNames);
                    $total = 'Rp ' . number_format($o->total_amount, 0, ',', '.');
                    
                    $allKnowledge->push([
                        'type' => 'Order',
                        'id' => $o->id,
                        'search_context' => "Riwayat Pesanan Anda (User): Nomor Pesanan #{$o->order_number}. Status: {$o->status}. Total: {$total}. Isi Pesanan: {$itemsStr}. Resi: {$o->tracking_number}.",
                        'item' => $o
                    ]);
                }
            }

            $topKnowledge = collect();

            if ($allKnowledge->count() > 0) {
                // =======================================================
                // HYBRID SEARCH TAHAP 1: EXACT KEYWORD MATCH (Pencarian Kata Kunci)
                // =======================================================
                $msgLower = strtolower($userMessage);
                
                // Kata kunci spesial agar AI memprioritaskan data tertentu jika ditanya
                $wantsOrder = str_contains($msgLower, 'pesanan') || str_contains($msgLower, 'order') || str_contains($msgLower, 'resi');
                $wantsBranch = str_contains($msgLower, 'cabang') || str_contains($msgLower, 'toko') || str_contains($msgLower, 'lokasi');
                $wantsReview = str_contains($msgLower, 'review') || str_contains($msgLower, 'ulasan') || str_contains($msgLower, 'rating') || str_contains($msgLower, 'bintang');
                
                $keywordMatches = $allKnowledge->filter(function ($k) use ($msgLower, $wantsOrder, $wantsBranch, $wantsReview) {
                    // Boost based on explicit intent
                    if ($wantsOrder && $k['type'] === 'Order') return true;
                    if ($wantsBranch && $k['type'] === 'Branch') return true;
                    if ($wantsReview && $k['type'] === 'Review') return true;

                    // Normal keyword matching
                    return str_contains(strtolower($k['search_context']), $msgLower);
                });

                // =======================================================
                // HYBRID SEARCH TAHAP 2: VECTOR SIMILARITY (Pencarian Makna)
                // =======================================================
                $sentences = $allKnowledge->pluck('search_context')->toArray();
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
                            $scoredKnowledge = $allKnowledge->map(function ($k, $index) use ($scores) {
                                $k['similarity_score'] = $scores[$index] ?? 0;
                                return $k;
                            });
                            // Ambil 15 context yang maknanya paling relevan (karena data sekarang lebih banyak)
                            $vectorMatches = $scoredKnowledge->sortByDesc('similarity_score')->take(15);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Koneksi HF Similarity bermasalah: ' . $e->getMessage());
                }

                // =======================================================
                // GABUNGKAN HASIL TAHAP 1 & TAHAP 2
                // =======================================================
                $topKnowledge = $keywordMatches->merge($vectorMatches)
                    ->unique(function ($item) {
                        return $item['type'] . '_' . $item['id'];
                    })
                    ->take(10); // Ambil 10 teratas untuk dikirim ke LLM

                if ($topKnowledge->isEmpty()) {
                    // Fallback jika API mati & tidak ada keyword yang cocok
                    $topKnowledge = $allKnowledge->take(5);
                }
            }

            // 3. Susun teks katalog final
            $contextText = "";
            foreach ($topKnowledge as $k) {
                $contextText .= "- [{$k['type']}] {$k['search_context']}\n";
            }

            // 4. Susun System Prompt
            $systemPrompt = "Anda adalah Scenty, asisten AI mewah dan cerdas untuk butik parfum 'Scentify'.\n";
            $systemPrompt .= "=== INFORMASI USER ===\n" . $userInfoContext . "\n======================\n\n";
            $systemPrompt .= "=== KONTEKS RELEVAN (Produk, Cabang, Review, atau Pesanan User) ===\n" . $contextText . "\n===================================================================\n\n";
            $systemPrompt .= "Aturan Komunikasi:\n";
            $systemPrompt .= "1. Selalu sapa user secara personal memanfaatkan data INFORMASI USER.\n";
            $systemPrompt .= "2. Jika user bertanya tentang pesanan mereka, rujuk ke KONTEKS RELEVAN yang memiliki label [Order].\n";
            $systemPrompt .= "3. Jika user bertanya cabang toko, rujuk ke KONTEKS RELEVAN berlabel [Branch].\n";
            $systemPrompt .= "4. Jika bertanya pendapat/review orang, rujuk ke KONTEKS RELEVAN berlabel [Review].\n";
            $systemPrompt .= "5. Jika bertanya produk, rujuk ke KONTEKS RELEVAN berlabel [Product]. Berikan informasi harga/ukuran yang akurat.\n";
            $systemPrompt .= "6. Jawab dengan Bahasa Indonesia yang elegan, profesional, penuh sopan santun khas butik mewah. Jelaskan dengan lengkap tapi padat.";

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
