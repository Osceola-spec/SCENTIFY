<?php

$brands = [
    'Chanel', 'Maison Francis Kurkdjian', 'HMNS', 'Le Labo', 
    'Armani', 'Dior', 'Carl & Claire', 'Byredo',
    'Tom Ford', 'Yves Saint Laurent', 'Versace', 'Jo Malone',
    'Bvlgari', 'Creed', 'Prada', 'Paco Rabanne',
    'Alchemist', 'Saff & Co', 'Zara', 'Gucci', 'Hermes', 
    'Dolce & Gabbana', 'Calvin Klein', 'Hugo Boss', 'Montblanc', 
    'Issey Miyake', 'Mykonos', 'Kahf', 'Oullu', 'Evangeline'
];

$notes = ['Woody', 'Citrus', 'Sweet', 'Floral', 'Spicy', 'Leather', 'Aquatic', 'Fresh', 'Tobacco', 'Vanilla', 'Musk', 'Fruity', 'Aromatic', 'Amber', 'Powdery', 'Green', 'Earthy'];

$images = [
    'https://images.unsplash.com/photo-1523293182086-7651a899d37f?auto=format&fit=crop&q=80&w=400',
    'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&q=80&w=400',
    'https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?auto=format&fit=crop&q=80&w=400',
    'https://images.unsplash.com/photo-1615397323136-1e0f074d3da9?auto=format&fit=crop&q=80&w=400',
    'https://images.unsplash.com/photo-1595532542520-50d220b30d31?auto=format&fit=crop&q=80&w=400',
    'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&q=80&w=400',
    'https://images.unsplash.com/photo-1592914610354-fd354d45e5b0?auto=format&fit=crop&q=80&w=400',
    'https://images.unsplash.com/photo-1590156156108-9ba249f07897?auto=format&fit=crop&q=80&w=400',
    'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?auto=format&fit=crop&q=80&w=400',
    'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&q=80&w=400',
    'https://images.unsplash.com/photo-1615160253813-2423ebdc6bba?auto=format&fit=crop&q=80&w=400',
    'https://images.unsplash.com/photo-1608528577891-eb055944f2e7?auto=format&fit=crop&q=80&w=400',
    'https://images.unsplash.com/photo-1587556200632-15f9e80277bd?auto=format&fit=crop&q=80&w=400',
    'https://images.unsplash.com/photo-1563170351-1365886c8f05?auto=format&fit=crop&q=80&w=400',
    'https://images.unsplash.com/photo-1557170334-a9632e77c6e4?auto=format&fit=crop&q=80&w=400',
    'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&q=80&w=400',
    'https://images.unsplash.com/photo-1629198688000-71f23e745b6e?auto=format&fit=crop&q=80&w=400',
    'https://images.unsplash.com/photo-1593000407137-020dfc428df1?auto=format&fit=crop&q=80&w=400'
];

$products = [
    // Chanel
    ['name' => 'Bleu de Chanel', 'brand' => 'Chanel', 'category' => 'Designer', 'type' => 'Men', 'price' => 2850000, 'notes' => ['Woody', 'Citrus', 'Aromatic']],
    ['name' => 'Coco Mademoiselle', 'brand' => 'Chanel', 'category' => 'Designer', 'type' => 'Women', 'price' => 3100000, 'notes' => ['Citrus', 'Patchouli', 'Floral']],
    ['name' => 'Allure Homme Sport', 'brand' => 'Chanel', 'category' => 'Designer', 'type' => 'Men', 'price' => 2600000, 'notes' => ['Fresh', 'Citrus', 'Aquatic']],
    
    // MFK
    ['name' => 'Baccarat Rouge 540', 'brand' => 'Maison Francis Kurkdjian', 'category' => 'Niche', 'type' => 'Unisex', 'price' => 6500000, 'notes' => ['Sweet', 'Woody', 'Amber']],
    ['name' => 'Grand Soir', 'brand' => 'Maison Francis Kurkdjian', 'category' => 'Niche', 'type' => 'Unisex', 'price' => 5200000, 'notes' => ['Amber', 'Vanilla', 'Sweet']],
    ['name' => 'Oud Satin Mood', 'brand' => 'Maison Francis Kurkdjian', 'category' => 'Niche', 'type' => 'Unisex', 'price' => 6800000, 'notes' => ['Floral', 'Woody', 'Vanilla']],

    // HMNS
    ['name' => 'Orgasm', 'brand' => 'HMNS', 'category' => 'Local', 'type' => 'Women', 'price' => 325000, 'notes' => ['Floral', 'Fruity', 'Vanilla']],
    ['name' => 'Alpha', 'brand' => 'HMNS', 'category' => 'Local', 'type' => 'Men', 'price' => 320000, 'notes' => ['Fresh', 'Woody', 'Earthy']],
    ['name' => 'EOS', 'brand' => 'HMNS', 'category' => 'Local', 'type' => 'Unisex', 'price' => 340000, 'notes' => ['Vanilla', 'Sweet', 'Powdery']],

    // Le Labo
    ['name' => 'Santal 33', 'brand' => 'Le Labo', 'category' => 'Niche', 'type' => 'Unisex', 'price' => 4500000, 'notes' => ['Woody', 'Powdery', 'Leather']],
    ['name' => 'Another 13', 'brand' => 'Le Labo', 'category' => 'Niche', 'type' => 'Unisex', 'price' => 4600000, 'notes' => ['Musk', 'Woody', 'Amber']],
    
    // Armani
    ['name' => 'Acqua Di Gio', 'brand' => 'Armani', 'category' => 'Designer', 'type' => 'Men', 'price' => 2100000, 'notes' => ['Aquatic', 'Fresh', 'Citrus']],
    ['name' => 'Acqua Di Gio Profumo', 'brand' => 'Armani', 'category' => 'Designer', 'type' => 'Men', 'price' => 2400000, 'notes' => ['Aquatic', 'Woody', 'Spicy']],
    ['name' => 'My Way', 'brand' => 'Armani', 'category' => 'Designer', 'type' => 'Women', 'price' => 2200000, 'notes' => ['Floral', 'Sweet', 'Vanilla']],

    // Dior
    ['name' => 'Sauvage EDT', 'brand' => 'Dior', 'category' => 'Designer', 'type' => 'Men', 'price' => 2400000, 'notes' => ['Fresh', 'Spicy', 'Amber']],
    ['name' => 'Sauvage Elixir', 'brand' => 'Dior', 'category' => 'Designer', 'type' => 'Men', 'price' => 3800000, 'notes' => ['Spicy', 'Woody', 'Lavender']],
    ['name' => 'Miss Dior', 'brand' => 'Dior', 'category' => 'Designer', 'type' => 'Women', 'price' => 2800000, 'notes' => ['Floral', 'Sweet', 'Powdery']],

    // Tom Ford
    ['name' => 'Ombre Leather', 'brand' => 'Tom Ford', 'category' => 'Designer', 'type' => 'Unisex', 'price' => 3100000, 'notes' => ['Leather', 'Spicy', 'Earthy']],
    ['name' => 'Tobacco Vanille', 'brand' => 'Tom Ford', 'category' => 'Designer', 'type' => 'Unisex', 'price' => 4200000, 'notes' => ['Tobacco', 'Vanilla', 'Sweet']],
    ['name' => 'Oud Wood', 'brand' => 'Tom Ford', 'category' => 'Designer', 'type' => 'Unisex', 'price' => 4500000, 'notes' => ['Woody', 'Spicy', 'Amber']],

    // YSL
    ['name' => 'Y EDP', 'brand' => 'Yves Saint Laurent', 'category' => 'Designer', 'type' => 'Men', 'price' => 2400000, 'notes' => ['Fresh', 'Aromatic', 'Woody']],
    ['name' => 'Libre EDP', 'brand' => 'Yves Saint Laurent', 'category' => 'Designer', 'type' => 'Women', 'price' => 2600000, 'notes' => ['Floral', 'Lavender', 'Vanilla']],
    ['name' => 'La Nuit de L\'Homme', 'brand' => 'Yves Saint Laurent', 'category' => 'Designer', 'type' => 'Men', 'price' => 2100000, 'notes' => ['Spicy', 'Aromatic', 'Woody']],

    // Versace
    ['name' => 'Eros EDT', 'brand' => 'Versace', 'category' => 'Designer', 'type' => 'Men', 'price' => 1600000, 'notes' => ['Sweet', 'Fresh', 'Vanilla']],
    ['name' => 'Dylan Blue', 'brand' => 'Versace', 'category' => 'Designer', 'type' => 'Men', 'price' => 1700000, 'notes' => ['Aquatic', 'Fresh', 'Amber']],
    ['name' => 'Bright Crystal', 'brand' => 'Versace', 'category' => 'Designer', 'type' => 'Women', 'price' => 1800000, 'notes' => ['Floral', 'Fresh', 'Fruity']],

    // Creed
    ['name' => 'Aventus', 'brand' => 'Creed', 'category' => 'Niche', 'type' => 'Men', 'price' => 5800000, 'notes' => ['Fruity', 'Woody', 'Leather']],
    ['name' => 'Silver Mountain Water', 'brand' => 'Creed', 'category' => 'Niche', 'type' => 'Unisex', 'price' => 5200000, 'notes' => ['Fresh', 'Citrus', 'Musk']],
    ['name' => 'Green Irish Tweed', 'brand' => 'Creed', 'category' => 'Niche', 'type' => 'Men', 'price' => 5400000, 'notes' => ['Green', 'Fresh', 'Woody']],

    // Jo Malone
    ['name' => 'Wood Sage & Sea Salt', 'brand' => 'Jo Malone', 'category' => 'Niche', 'type' => 'Unisex', 'price' => 2500000, 'notes' => ['Aquatic', 'Woody', 'Fresh']],
    ['name' => 'English Pear & Freesia', 'brand' => 'Jo Malone', 'category' => 'Niche', 'type' => 'Women', 'price' => 2500000, 'notes' => ['Fruity', 'Floral', 'Fresh']],
    ['name' => 'Peony & Blush Suede', 'brand' => 'Jo Malone', 'category' => 'Niche', 'type' => 'Women', 'price' => 2500000, 'notes' => ['Floral', 'Leather', 'Fresh']],

    // Local Brands (Saff & Co, Alchemist, Mykonos, Kahf, Oullu, Carl & Claire)
    ['name' => 'CHNO', 'brand' => 'Saff & Co', 'category' => 'Local', 'type' => 'Unisex', 'price' => 249000, 'notes' => ['Vanilla', 'Sweet', 'Spicy']],
    ['name' => 'Louv', 'brand' => 'Saff & Co', 'category' => 'Local', 'type' => 'Women', 'price' => 219000, 'notes' => ['Floral', 'Musk', 'Fresh']],
    ['name' => 'Pink Laundry', 'brand' => 'Alchemist', 'category' => 'Local', 'type' => 'Women', 'price' => 279000, 'notes' => ['Fresh', 'Floral', 'Musk']],
    ['name' => 'Powder Room', 'brand' => 'Alchemist', 'category' => 'Local', 'type' => 'Women', 'price' => 279000, 'notes' => ['Powdery', 'Musk', 'Fresh']],
    ['name' => 'Vanilla Clouds', 'brand' => 'Mykonos', 'category' => 'Local', 'type' => 'Women', 'price' => 199000, 'notes' => ['Vanilla', 'Sweet', 'Powdery']],
    ['name' => 'Revered Oud', 'brand' => 'Kahf', 'category' => 'Local', 'type' => 'Men', 'price' => 75000, 'notes' => ['Woody', 'Sweet', 'Spicy']],
    ['name' => 'Invigorating Waterfall', 'brand' => 'Kahf', 'category' => 'Local', 'type' => 'Men', 'price' => 75000, 'notes' => ['Aquatic', 'Fresh', 'Citrus']],
    ['name' => 'Ego', 'brand' => 'Oullu', 'category' => 'Local', 'type' => 'Unisex', 'price' => 389000, 'notes' => ['Floral', 'Woody', 'Musk']],
    ['name' => 'Oud Batavia', 'brand' => 'Carl & Claire', 'category' => 'Local', 'type' => 'Men', 'price' => 299000, 'notes' => ['Woody', 'Tobacco', 'Spicy']],

    // Others (Gucci, Hermes, D&G, CK, Montblanc)
    ['name' => 'Gucci Flora', 'brand' => 'Gucci', 'category' => 'Designer', 'type' => 'Women', 'price' => 2400000, 'notes' => ['Floral', 'Fresh', 'Sweet']],
    ['name' => 'Terre d\'Hermes', 'brand' => 'Hermes', 'category' => 'Designer', 'type' => 'Men', 'price' => 2600000, 'notes' => ['Citrus', 'Woody', 'Earthy']],
    ['name' => 'Light Blue', 'brand' => 'Dolce & Gabbana', 'category' => 'Designer', 'type' => 'Women', 'price' => 1900000, 'notes' => ['Citrus', 'Fresh', 'Woody']],
    ['name' => 'The One', 'brand' => 'Dolce & Gabbana', 'category' => 'Designer', 'type' => 'Men', 'price' => 2100000, 'notes' => ['Amber', 'Tobacco', 'Spicy']],
    ['name' => 'CK One', 'brand' => 'Calvin Klein', 'category' => 'Designer', 'type' => 'Unisex', 'price' => 1200000, 'notes' => ['Citrus', 'Fresh', 'Green']],
    ['name' => 'CK Be', 'brand' => 'Calvin Klein', 'category' => 'Designer', 'type' => 'Unisex', 'price' => 1100000, 'notes' => ['Fresh', 'Musk', 'Woody']],
    ['name' => 'Explorer', 'brand' => 'Montblanc', 'category' => 'Designer', 'type' => 'Men', 'price' => 1500000, 'notes' => ['Woody', 'Fresh', 'Citrus']],
    ['name' => 'Legend', 'brand' => 'Montblanc', 'category' => 'Designer', 'type' => 'Men', 'price' => 1400000, 'notes' => ['Aromatic', 'Fruity', 'Fresh']],
    ['name' => 'L\'Eau d\'Issey', 'brand' => 'Issey Miyake', 'category' => 'Designer', 'type' => 'Women', 'price' => 1600000, 'notes' => ['Floral', 'Aquatic', 'Fresh']],
    ['name' => '1 Million', 'brand' => 'Paco Rabanne', 'category' => 'Designer', 'type' => 'Men', 'price' => 1750000, 'notes' => ['Sweet', 'Spicy', 'Leather']],
];

$output = \"\";
foreach ($products as $i => $p) {
    $img = $images[$i % count($images)];
    $discount = (rand(1, 10) > 7) ? rand(5, 20) : 0;
    $is_new = (rand(1, 10) > 8) ? 'true' : 'false';
    $notesStr = \"'\" . implode(\"', '\", $p['notes']) . \"'\";
    $output .= \"            [\\n\";
    $output .= \"                'name' => '{\$p['name']}', 'brand' => '{\$p['brand']}', 'category' => '{\$p['category']}', 'type' => '{\$p['type']}',\\n\";
    $output .= \"                'price' => {\$p['price']}, 'image' => '{\$img}',\\n\";
    $output .= \"                'notes' => [{\$notesStr}], 'is_new' => {\$is_new}, 'discount' => {\$discount}\\n\";
    $output .= \"            ],\\n\";
}

echo $output;
?>
