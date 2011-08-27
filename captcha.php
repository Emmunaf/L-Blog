<?php
header ("Content-Type: image/gif");
$sfondo = 'sfondo.gif'; 
$font   = 'font.ttf'; 

$img = imagecreatefromgif($sfondo);

$alfabeto = array("a", "b", "c", "d", "e", "f" ,"g" ,"h", "i", "l", "m", "n", "o", "p", "q", "r", "s", "t" ,"u", "v", "z");
$frase    = array();
$n = 0;
while($n < 4) {
    $r = rand(0,20);
    $nero = imageColorAllocate($img, 0, 0, 0);
    $frase[$n] .= $alfabeto[$r];
    $n++;
}


/*
for($i=0;$i<100;$i++) {
    $x1 = rand(3,$x-3);
    $y1 = rand(3,$y-3);
    $x2 = $x1-2-rand(0,8);
    $y2 = $y1-2-rand(0,8);
    imageline($img,$x1,$y1,$x2,$y2,$colors[rand(0,count($colors)-1)]);
}
*/
$content = $frase['0'].$frase['1'].$frase['2'];
imageString($img, 5, 100, 76, $content, $nero);
imagegif($img);

file_put_contents("cap.php",$content);
?>
