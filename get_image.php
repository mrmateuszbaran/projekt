<?php
	$conn = mysql_connect('mysql.hostinger.pl', 'u322806910_wieik', 'info3pk');
	mysql_select_db('u322806910_wieik');
	
	$content = mysql_fetch_row(mysql_query("SELECT Dane FROM obrazy WHERE Nazwa = '$_GET[nazwa]'"));
	$content = $content[0];
	$img = imagecreatefromstring($content);
	ob_start();
	imagejpeg($img);	
	$data = ob_get_clean();
	echo base64_encode($data);
	imagedestroy($img);
	
	mysql_close($conn);
?>