<?php
header('Content-type:image/png');

function hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec($hex[0].$hex[0]);
      $g = hexdec($hex[1].$hex[1]);
      $b = hexdec($hex[2].$hex[2]);
   } else {
      $r = hexdec($hex[0].$hex[1]);
      $g = hexdec($hex[2].$hex[3]);
      $b = hexdec($hex[4].$hex[5]);
   }
   return array($r, $g, $b); // returns an array with the rgb values
}

list($r,$g,$b) = hex2rgb($_GET[c]);

$logo = imagecreatefrompng('logo_transp.png');
imagealphablending($logo, true);
imagesavealpha($logo, true);
$width = imagesx($logo);
$height = imagesy($logo);

$logo_color = imagecreatetruecolor($width, $height);
imagesavealpha($logo_color, true);
#imagealphablending($logo_color, true);
$trans_colour = imagecolorallocatealpha($logo_color, 0, 0, 0, 127);
imagefill($logo_color, 0, 0, $trans_colour);

$theme_color = imagecolorallocate($logo_color, $r, $g, $b);
imagefilledrectangle($logo_color, 60, 60, 400, 400, $theme_color);

imagecopy($logo_color, $logo, 0, 0, 0, 0, $width, $height);

imagepng($logo_color);
imagedestroy($logo_color);

?>
