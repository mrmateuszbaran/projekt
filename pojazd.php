<?php
	include "funkcje.php";
	
	$komenda = $_GET['komenda'];
	$pojazd = $bd->pobierzDane($komenda, "pojazdy", "*", "idpojazd=$_GET[id]")[0];
	$obrazy = $bd->pobierzdane($komenda, "obrazy", "*", "idpojazd=$_GET[id]");
	$zbrodnie = $bd->wykonajZapytanie($komenda, "SELECT * FROM zbrodnia NATURAL RIGHT JOIN zbrodnia_pojazd NATURAL LEFT JOIN pojazdy WHERE idpojazd = $_GET[id]", true);
	
	echo "<h1>Pojazd</h1>";
	foreach($obrazy as $idx => $obraz)
	{
		$miniatura = generujMiniature($obraz['dane']);
		echo "<img src = \"".$miniatura."\" class = 'image-thumbnail' baza = '".$komenda."' id = '".$obraz['id']."' onClick = 'pokaz_obraz(this);' >";
	}
	echo "<hr>";
	echo "<h2>".$pojazd['nrrej']."</h2><hr>";
	echo $pojazd['marka']." ".$pojazd['model']."<br><hr>";
	echo "<h2>Powiązane zbrodnie: </h2>";
	if (count($zbrodnie) == 0)
		echo "Brak";
	else
		foreach($zbrodnie as $idx => $zbrodnia)
		{
			echo "<a href = zbrodnia.php?id=".$zbrodnia['idzbrodnia']."><div style = \"padding:5px; border:1px black solid;\">".$zbrodnia['opis']."<br>".$zbrodnia['autor'].", dnia ".$zbrodnia['data']."</div></a><br>";
		}
	echo "<br><hr>";
	echo "<div id = \"stopka\">Dodał: ".$pojazd['autor']." <span style=\"float:right;\">dnia ";
	echo $pojazd['data']."</span></div><br>";
	
?>