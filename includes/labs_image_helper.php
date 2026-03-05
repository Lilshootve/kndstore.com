<?php
/**
 * KND Labs - Image validation and conversion helpers
 */

/**
 * Normalize image to PNG. Supports PNG, JPG, WebP.
 * @param string $sourcePath Path to source image
 * @param string $destPath Path for output PNG
 * @return bool true on success
 */
function labs_image_to_png(string $sourcePath, string $destPath): bool {
    $img = @getimagesize($sourcePath);
    if (!$img || !isset($img[2])) return false;

    $src = null;
    switch ($img[2]) {
        case IMAGETYPE_PNG:
            $src = @imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_JPEG:
            $src = @imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_WEBP:
            if (function_exists('imagecreatefromwebp')) {
                $src = @imagecreatefromwebp($sourcePath);
            }
            break;
        default:
            return false;
    }
    if (!$src) return false;

    $ok = @imagepng($src, $destPath);
    @imagedestroy($src);
    return $ok;
}

/**
 * Validate image: max size (bytes), max dimension, allowed types.
 * @return array ['ok' => bool, 'error' => string|null]
 */
function labs_validate_image(string $path, int $maxBytes = 5242880, int $maxDim = 2048): array {
    if (!is_file($path) || !is_readable($path)) {
        return ['ok' => false, 'error' => 'File not readable'];
    }
    $size = filesize($path);
    if ($size === false || $size > $maxBytes) {
        return ['ok' => false, 'error' => 'Image max ' . round($maxBytes / 1024 / 1024, 1) . 'MB'];
    }
    $img = @getimagesize($path);
    if (!$img || !in_array($img[2] ?? 0, [IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_WEBP], true)) {
        return ['ok' => false, 'error' => 'Image must be PNG, JPG or WebP'];
    }
    $w = $img[0] ?? 0;
    $h = $img[1] ?? 0;
    if ($w > $maxDim || $h > $maxDim) {
        return ['ok' => false, 'error' => 'Image max ' . $maxDim . 'px per side'];
    }
    return ['ok' => true, 'error' => null];
}
