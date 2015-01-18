<?php 
	
	if (!isset($bd))
	{
		include 'baza.php';
		$bd = new BazaDanych();
	}
	
	include 'user.php';
	$user = null;
	
?>