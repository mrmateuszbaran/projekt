<?php
	header("Content-Type: text/plain");
	$conn = mysql_connect('mysql.hostinger.pl', 'u322806910_wieik', 'info3pk');
	mysql_select_db('u322806910_wieik');
	mysql_set_charset("UTF8");
	
	$opis = mysql_fetch_array(mysql_query("SELECT Nazwa, Autor, Opis FROM obrazy WHERE Nazwa = '".mysql_real_escape_string($_GET[nazwa])."'"));

	//print_r($opis);
	echo "<div id = \"obraz-opis-nazwa\">".$opis['Nazwa']."</div>";
	
	echo "<div id = \"obraz-opis-autor\">".$opis['Autor']."</div>";
	
	if ($opis['Opis'] == "")
		echo "<div id = \"obraz-opis-tresc\">Brak opisu</div>";
	else
		echo "<div id = \"obraz-opis-tresc\">".$opis['Opis']."</div>";
		
	
	mysql_close($conn);
?>