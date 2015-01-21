<?php
	include "funkcje.php";
	
	$komenda = "Lokal";
	if (isset($_POST['komenda']))
	{
		$komenda = $_POST['komenda'];
	}
	$pojazdy = $bd->pobierzDane($komenda, "pojazdy");
	
	echo "<h1>Pojazdy</h1>";
	$ile_w_lini = 4;
	$i = 0;
	foreach($pojazdy as $idx => $pojazd)
	{
		if (isset($pojazd['miniatura']))
		{
			$uri = $pojazd['miniatura'];
			$obraz = true;
		} else
		{
			$obraz = false;
		}
		echo "<a href = \"?strona=pojazd&komenda=$pojazd[baza]&id=$pojazd[idpojazd]\" onMouseOut = \"toggle_tooltip(this);\" onMouseOver = \"toggle_tooltip(this);\"><img class = 'image-thumbnail' id = '".$pojazd['idpojazd']."' baza = '".$pojazd['baza']."' src = ".($obraz ? $uri : "res/brakobrazu.png")."></a>";
	
		echo "<div class = \"miejsce-div\">";
		echo "<div class = \"miejsce-div-baza\">".$pojazd['baza']."</div>";
		echo "<div class = \"miejsce-div-adres\">".str_replace("\\r\\n", "<br>", mysql_real_escape_string($pojazd['nrrej']))."</div>";
		echo "<div class = \"miejsce-div-autor\">".$pojazd['marka']."</div>";
		echo "<div class = \"miejsce-div-data\">".$pojazd['model']."</div>";
		echo "</div>";
		if (++$i >= $ile_w_lini)
		{
			$i = 0;
			echo "<br>";
		}
		
	}
?>