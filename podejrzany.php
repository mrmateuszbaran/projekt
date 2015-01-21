<?php
	include "funkcje.php";
	
	//TODO
	// mysql real escape char!
	
	$komenda = $_GET['komenda'];
	$podejrzany = $bd->pobierzDane($komenda, "podejrzani", "*", "idpodejrzany=$_GET[id]")[0];
	$obrazy = $bd->pobierzdane($komenda, "obrazy", "*", "idpodejrzany=$_GET[id]");
	$zbrodnie = $bd->wykonajZapytanie($komenda, "SELECT * FROM zbrodnia NATURAL RIGHT JOIN zbrodnia_podejrzany NATURAL LEFT JOIN podejrzani WHERE idpodejrzany = $_GET[id]", true);
	$wyroki = $bd->wykonajZapytanie($komenda, "SELECT * FROM wyrok NATURAL JOIN podejrzani WHERE idpodejrzany = $_GET[id]", true);
	
	
	echo "<h1>Podejrzany</h1>";
	foreach($obrazy as $idx => $obraz)
	{
		$miniatura = generujMiniature($obraz['dane']);
		echo "<img src = \"".$miniatura."\" class = 'image-thumbnail' baza = '".$komenda."' id = '".$obraz['id']."' onClick = 'pokaz_obraz(this);' >";
	}
	echo "<hr>";
	echo "<h2>".$podejrzany['imie']." ".$podejrzany['nazwisko']."</h2><hr>";
	echo "PESEL: <b>".(empty($podejrzany['pesel']) ? "Brak danych" : $podejrzany['pesel'])."</b><br><hr>";
	echo "<h2>Powiązane zbrodnie: </h2>";
	if (count($zbrodnie) == 0)
		echo "Brak";
	else
		foreach($zbrodnie as $idx => $zbrodnia)
		{
			echo "<a href = zbrodnia.php?id=".$zbrodnia['idzbrodnia']."><div style = \"padding:5px; border: 1px black solid;\">".$zbrodnia['opis']."<br>".$zbrodnia['autor'].", dnia ".$zbrodnia['data']."</div></a><br>";
		}
	echo "<br><hr>";
	echo "<h2>Wyroki na koncie: </h2>";
	if (count($wyroki) == 0)
		echo "Brak";
	else
		foreach($wyroki as $idx => $wyrok)
		{
			echo "<div style = \"padding:5px; border: 1px black solid;\">".$wyrok['kara'].": ".$wyrok['wartosc']."</div><br>";
		}
	echo "<br><hr>";
	echo "<div id = \"stopka\">Dodał: ".$podejrzany['autor']." <span style=\"float:right;\">dnia ";
	echo $podejrzany['data']."</span></div><br>";
	
	
	
?>