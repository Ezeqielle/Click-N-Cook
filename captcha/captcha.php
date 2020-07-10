<?php
session_start();

$char = '1234567890ABCDEFGHIJKLMOPQRSTUVWXY1234567890abcdefghijklmnopqrstuvwxyz1234567890';
header("Content-type: image/png");
$font = 'fonts/pacifico.ttf';
$charShuffle = substr(str_shuffle($char), 0, 5); //mélange les caractères et prends les 5 premiers caractères
$_SESSION['captcha'] = $charShuffle;
$image = imagecreate(160, 50);
$background = imagecolorallocate($image, 247, 247, 247);
$color = imagecolorallocate($image, 0, 0, 0);

imagettftext($image, 20, 0, 15, 30, $color, $font, $charShuffle);
imagepng($image);
imagedestroy($image);
?>