<?php
	include "globals.php";
	
	$content = $bd->wykonajZapytanie($_GET['baza'], "SELECT dane FROM obrazy WHERE id = '$_GET[id]'")['dane'];
	//$img = imagecreatefromstring($content);
	//ob_start();
	//imagejpeg($img);	
	//$data = ob_get_clean();
	echo base64_encode($content);
	//imagedestroy($img);
	
?>