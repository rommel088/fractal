<?php
/* Множество Мандельброта. */
/* Время создания */
set_time_limit(120);
function re_microtime() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec); }
/* Засекаем */
$time_start = re_microtime();

/* Размер картинки */
$img_w = 900;
$img_h = 600;

/* Начало и конец чертежа */
$x_min = -2;
$x_max = 1;
$y_min = -1;
$y_max = 1;

/* Подсчёт шага */
if($x_min >= 0 && $x_max >= 0){
    $step = ($x_min + $x_max)/$img_w;
} elseif($x_min < 0 && $x_max >= 0) {
    $step = ($x_max - $x_min)/$img_w;
} else {
    $step = (-$x_min + $x_max)/$img_w; }

$img = imagecreatetruecolor($img_w,$img_h);
$c = array();

$yy = 0;
for($y = $y_min; $y < $y_max; $y = $y + $step){
    $xx = 0;
    for($x = $x_min; $x < $x_max; $x = $x + $step){

        $c['x'] = $x;
        $c['y'] = $y;
        $X = $x;
        $Y = $y;
        $ix=0; $iy=0; $n=0;

        while(($ix*$ix + $iy*$iy < 5) and ($n < 64)){
            $ix = $X*$X - $Y*$Y + $c['x'];
            $iy = 2*$X*$Y + $c['y'];
            $X = $ix;
            $Y = $iy;
            $n++;
        }

        $col = imagecolorallocate($img, 255-$n*5, 0, 0);
        imagesetpixel($img, $xx, $yy, $col);

        $xx++; }
    $yy++; }

$time_end = re_microtime();
header("Content-type: image/png");
/* выводим в заголовках время создания */
header ("X-Exec-Time: ".($time_end - $time_start));
imagepng($img);
imagedestroy($img);