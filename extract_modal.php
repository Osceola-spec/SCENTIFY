<?php
$shopPath = 'resources/views/shop.blade.php';
$lines = file($shopPath);

$modalStart = -1;
$modalEnd = -1;
$jsStart = -1;
$jsEnd = -1;

for ($i = 0; $i < count($lines); $i++) {
    if (strpos($lines[$i], '<div id="variantModal"') !== false && $modalStart === -1) {
        $modalStart = $i;
    }
    if (strpos($lines[$i], '</form>') !== false && $modalStart !== -1 && $modalEnd === -1) {
        if (strpos($lines[$i-1], '<input type="hidden" name="quantity"') !== false) {
            $modalEnd = $i;
        }
    }
    if (strpos($lines[$i], 'function openVariantModal(btn)') !== false && $jsStart === -1) {
        $jsStart = $i; 
    }
    if (strpos($lines[$i], 'function toggleWishlist(btn, event, productId)') !== false && $jsEnd === -1) {
        $jsEnd = $i - 1;
    }
}

$modalHtml = array_slice($lines, $modalStart, $modalEnd - $modalStart + 1);
$modalJs = array_slice($lines, $jsStart, $jsEnd - $jsStart);

$partialContent = implode('', $modalHtml) . "\n<script>\n" . implode('', $modalJs) . "</script>\n";
file_put_contents('resources/views/partials/variant_modal.blade.php', $partialContent);

array_splice($lines, $jsStart, $jsEnd - $jsStart);
array_splice($lines, $modalStart, $modalEnd - $modalStart + 1, ["    @include('partials.variant_modal')\n"]);

file_put_contents($shopPath, implode('', $lines));
echo 'Done';
