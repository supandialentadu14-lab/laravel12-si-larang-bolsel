<?php

$images = [
    'public/images/silarang-logo.png' => 'public/images/silarang-logo.webp',
    'public/images/3d-bag.png' => 'public/images/3d-bag.webp',
    'public/images/3d-presenter.png' => 'public/images/3d-presenter.webp',
    'public/images/3d-running.png' => 'public/images/3d-running.webp',
    'public/images/3d-wallet.png' => 'public/images/3d-wallet.webp',
    'public/images/bolsel.png' => 'public/images/bolsel.webp',
];

foreach ($images as $source => $target) {
    if (!file_exists($source)) continue;
    $image = imagecreatefrompng($source);
    imagealphablending($image, false);
    imagesavealpha($image, true);
    
    // Resize logo to max 256 for web use
    if (str_contains($source, 'logo')) {
        $image = imagescale($image, 256);
    }

    imagewebp($image, $target, 80);
    imagedestroy($image);
    echo "Converted $source to WebP ($target)\n";
}
