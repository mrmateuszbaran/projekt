<?php
// TODO
// Klasa obrazu !
/////////////////////////////////////////////

function generujMiniature($dane)
{
	$new_width = 120;
	$new_height = 90;
	$new_AR = $new_width / $new_height;

	$imageData = imagecreatefromstring($dane);
	
	$old_width = imageSX($imageData);
	$old_height = imageSY($imageData);
	$old_AR = $old_width / $old_height;
	
	if($old_AR > $new_AR) 
	{
		$w = $old_height * $new_AR;
		$h = $old_height;
		$x = ($old_width - $w) / 2;
		$y = 0;
	}
	if($old_AR < $new_AR) 
	{
		$w = $old_width;
		$h = $old_width / $new_AR;
		$x = 0;
		$y = ($old_height - $h) / 2;
	}
	if($old_AR == $new_AR) 
	{
		$w = $old_width;
		$h = $old_height;
		$x = 0;
		$y = 0;
	}
	
	$thumbData = imagecreatetruecolor($new_width, $new_height);
	imagecopyresampled($thumbData, $imageData, 0, 0, $x, $y, $new_width, $new_height, $w, $h);
	
	ob_start();
	imagejpeg($thumbData);
	$data = ob_get_clean();
	imagedestroy($imageData);
	imagedestroy($thumbData);
	return("data:image/jpeg;base64,".base64_encode($data));
}
?>