<?php
	include "funkcje.php";
	
	$komenda = "Lokal";
	if (isset($_POST['komenda']))
	{
		$komenda = $_POST['komenda'];
	}
	$podejrzani = $bd->pobierzDane($komenda, "podejrzani");
	
	echo "<h1>Podejrzani</h1>";
	$ile_w_lini = 4;
	$i = 0;
	foreach($podejrzani as $idx => $podejrzany)
	{
		if (isset($podejrzany['miniatura']))
		{
			$uri = $podejrzany['miniatura'];
			$obraz = true;
		} else
		{
			$obraz = false;
		}
		echo "<a href = \"?strona=podejrzany&komenda=$podejrzany[baza]&id=$podejrzany[idpodejrzany]\" onMouseOut = \"toggle_tooltip(this);\" onMouseOver = \"toggle_tooltip(this);\"><img class = 'image-thumbnail' id = '".$podejrzany['idpodejrzany']."' baza = '".$podejrzany['baza']."' src = ".($obraz ? $uri : "res/brakobrazu.png")."></a>";
	
		echo "<div class = \"miejsce-div\">";
		echo "<div class = \"miejsce-div-baza\">".$podejrzany['baza']."</div>";
		echo "<div class = \"podejrzany-div-dane\">".str_replace("\\r\\n", "<br>", $podejrzany['imie'])." ".$podejrzany['nazwisko']."<br>PESEL: ".$podejrzany['pesel']."</div>";
		echo "<div class = \"miejsce-div-autor\">".$podejrzany['autor']."</div>";
		echo "<div class = \"miejsce-div-data\">".$podejrzany['data']."</div>";
		echo "</div>";
		if (++$i >= $ile_w_lini)
		{
			$i = 0;
			echo "<br>";
		}
		
	}
?>