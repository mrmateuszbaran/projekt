<?php
	if (!isset($bd))
	{
		include "baza.php";
		$bd = new BazaDanych();
	}
	
	$content = $bd->wykonajZapytanie($_GET['baza'], "SELECT dane FROM obrazy WHERE id = $_GET[id]", true)[0]['dane'];
	$img = imagecreatefromstring($content);
	ob_start();
	imagepng($img);	
	$data = ob_get_clean();
	imagedestroy($img);
	
	echo base64_encode($data);
	
?>