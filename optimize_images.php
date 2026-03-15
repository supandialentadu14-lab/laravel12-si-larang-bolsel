<?php

$images = [
    'public/images/login-bg-new.png' => 'public/images/login-bg-new.webp',
    'public/images/silarang-logo.png' => 'public/images/silarang-logo-small.png',
    'public/images/3d-bag.png' => 'public/images/3d-bag-small.png',
    'public/images/3d-presenter.png' => 'public/images/3d-presenter-small.png',
    'public/images/3d-running.png' => 'public/images/3d-running-small.png',
    'public/images/3d-wallet.png' => 'public/images/3d-wallet-small.png',
];

foreach ($images as $source => $target) {
    if (!file_exists($source)) {
        echo "Source not found: $source\n";
        continue;
    }

    $info = getimagesize($source);
    $mime = $info['mime'];

    switch ($mime) {
        case 'image/png':
            $image = imagecreatefrompng($source);
            imagealphablending($image, false);
            imagesavealpha($image, true);
            break;
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        default:
            echo "Unsupported format: $mime for $source\n";
            continue 2;
    }

    $extension = pathinfo($target, PATHINFO_EXTENSION);
    
    // Scale down if very large
    $width = imagesx($image);
    $height = imagesy($image);
    if ($width > 2000) {
        $newWidth = 1920;
        $newHeight = ($height / $width) * $newWidth;
        $image = imagescale($image, $newWidth, $newHeight);
    }

    if ($extension === 'webp') {
        imagewebp($image, $target, 80);
    } elseif ($extension === 'png') {
        // PNG compression level 9 (max)
        imagepng($image, $target, 9);
    }

    imagedestroy($image);
    
    $oldSize = round(filesize($source) / 1024 / 1024, 2);
    $newSize = round(filesize($target) / 1024 / 1024, 2);
    echo "Optimized $source ($oldSize MB) -> $target ($newSize MB)\n";
}
