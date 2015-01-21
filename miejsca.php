<?php
	include "funkcje.php";
	
	$komenda = "*";
	if (isset($_POST['komenda']))
	{
		$komenda = $_POST['komenda'];
	}
	$miejsca = $bd->pobierzDane($komenda, "miejsca");
	
	echo "<h1>Miejsca zbrodni</h1>";
	$ile_w_lini = 4;
	$i = 0;
	foreach($miejsca as $idx => $miejsce)
	{
		if (isset($miejsce['miniatura']))
		{
			$uri = $miejsce['miniatura'];
			$obraz = true;
		} else
		{
			$obraz = false;
		}
		echo "<a href = \"?strona=miejsce&komenda=$miejsce[baza]&id=$miejsce[idmiejsce]\" onMouseOut = \"toggle_tooltip(this);\" onMouseOver = \"toggle_tooltip(this);\"><img class = 'image-thumbnail' id = '".$miejsce['idmiejsce']."' baza = '".$miejsce['baza']."' src = ".($obraz ? $uri : "res/brakobrazu.png")."></a>";
	
		echo "<div class = \"miejsce-div\">";
		echo "<div class = \"miejsce-div-baza\">".$miejsce['baza']."</div>";
		echo "<div class = \"miejsce-div-adres\">".str_replace("\\r\\n", "<br>", mysql_real_escape_string($miejsce['adres']))."</div>";
		echo "<div class = \"miejsce-div-autor\">".$miejsce['autor']."</div>";
		echo "<div class = \"miejsce-div-data\">".$miejsce['data']."</div>";
		echo "</div>";
		if (++$i >= $ile_w_lini)
		{
			$i = 0;
			echo "<br>";
		}
		
	}
?>