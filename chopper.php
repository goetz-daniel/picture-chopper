<?php

/**
 * Picture Chopper – Download protection for images.
 *
 * Splits an image into a grid of small tiles, randomly rotates each tile,
 * base64-encodes them, shuffles their order in the DOM, and reassembles them
 * visually with CSS Grid + counter-rotations.
 *
 * @param  string $url   URL or local path of the source image.
 * @param  int    $tiles Approximate number of tile divisions along the shortest side.
 * @return string        Self-contained HTML snippet that displays the protected image.
 */
function picture_chopper(string $url, int $tiles = 3): string
{
    $data = @file_get_contents($url);
    if (!$data) return '';
    $img = @imagecreatefromstring($data);
    if (!$img) return '';

    $w = imagesx($img);
    $h = imagesy($img);
    $s = (int)max(10, round(min($w, $h) / max(1, $tiles)));
    $cols = (int)floor($w / $s);
    $rows = (int)floor($h / $s);
    $td = [];
    $buf = imagecreatetruecolor($s, $s);

    for ($r = 0; $r < $rows; $r++) {
        for ($c = 0; $c < $cols; $c++) {
            imagecopy($buf, $img, 0, 0, $c * $s, $r * $s, $s, $s);

            // Randomly rotate each tile to scramble it in the stored data
            $deg = [90, 180, 270][random_int(0, 2)];
            $rot = imagerotate($buf, $deg, 0);

            ob_start();
            imagewebp($rot, null, 80);
            $td[] = [($r + 1) . '/' . ($c + 1), $deg, base64_encode(ob_get_clean())];
            imagedestroy($rot);
        }
    }
    imagedestroy($buf);
    imagedestroy($img);

    $pw = $cols * $s;
    $ph = $rows * $s;
    $style = 'display:inline-grid;max-width:100%;overflow:hidden'
        . ';width:' . $pw . 'px;aspect-ratio:' . $pw . '/' . $ph
        . ';grid-template-columns:repeat(' . $cols . ',1fr)'
        . ';grid-template-rows:repeat(' . $rows . ',1fr)';

    $html = '<div style="' . htmlspecialchars($style, ENT_QUOTES) . '">';

    // Shuffle so DOM order doesn't reveal the original tile layout
    shuffle($td);

    foreach ($td as [$area, $deg, $b64]) {
        // grid-area places the tile, transform counter-rotates it back to correct orientation
        $html .= '<div style="grid-area:' . $area
            . ';background:url(data:image/webp;base64,' . $b64 . ') 0/cover'
            . ';transform:rotate(' . $deg . 'deg)"></div>';
    }

    return $html . '</div>';
}
