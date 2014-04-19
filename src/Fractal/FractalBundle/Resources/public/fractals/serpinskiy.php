<?php
set_time_limit(5);

$i = 6;		// Количество итераций
$xy = 1500;	// Размер стороны картинки

$img = imagecreatetruecolor($xy, $xy);

$black = imagecolorallocate($img, 0, 0, 0);
$white = imagecolorallocate($img, 255, 255, 255);

$cycle = 0;
drawCarpet(0, 0, $xy, $xy, $i);
function drawCarpet($a, $b, $c, $d, $n) {
    global $img, $white, $cycle;
    $cycle++;

    if($n <= 0) return;

    $a1 = 2 * $a / 3 + $c / 3;
    $c1 = $a / 3 + 2 * $c / 3;
    $b1 = 2 * $b / 3 + $d / 3;
    $d1 = $b / 3 + 2 * $d / 3;

    imagefilledrectangle($img, $a1, $b1, $c1, $d1, $white);

    drawCarpet($a, $b, $a1, $b1, $n - 1);
    drawCarpet($a1, $b, $c1, $b1, $n - 1);
    drawCarpet($c1, $b, $c, $b1, $n - 1);

    drawCarpet($a, $b1, $a1, $d1, $n - 1);
    drawCarpet($c1, $b1, $c, $d1, $n - 1);

    drawCarpet($a, $d1, $a1, $d, $n - 1);
    drawCarpet($a1, $d1, $c1, $d, $n - 1);
    drawCarpet($c1, $d1, $c, $d, $n - 1);
}

imagefilledrectangle($img, 0, 0, (strlen($cycle) * 9) , 16, $white);
imagestring($img,21,0,0,$cycle,$black);

header('Content-Type: image/png');
imagepng($img);