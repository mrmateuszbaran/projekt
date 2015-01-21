<?php
	include "funkcje.php";
	
	$komenda = $_GET['komenda'];
	$miejsce = $bd->pobierzDane($komenda, "miejsca", "*", "idmiejsce=$_GET[id]")[0];
	$obrazy = $bd->pobierzdane($komenda, "obrazy", "*", "idmiejsce=$_GET[id]");
	$zbrodnie = $bd->pobierzDane($komenda, "zbrodnia", "*", "idmiejsce=$_GET[id]");
	
	echo "<h1>Miejsce zbrodni</h1>";
	foreach($obrazy as $idx => $obraz)
	{
		$miniatura = generujMiniature($obraz['dane']);
		echo "<img src = \"".$miniatura."\" class = 'image-thumbnail' baza = '".$komenda."' id = '".$obraz['id']."' onClick = 'pokaz_obraz(this);' >";
	}
	echo "<hr>";
	echo "<h2>".$miejsce['adres']."</h2><hr>";
	echo (empty($miejsce['opis']) ? "Brak opisu" : $miejsce['opis'])."<br><hr>";
	echo "<h2>Powiązane zbrodnie: </h2>";
	if (count($zbrodnie) == 0)
		echo "Brak";
	else
		foreach($zbrodnie as $idx => $zbrodnia)
		{
			echo "<a href = zbrodnia.php?id=".$zbrodnia['idzbrodnia']."><div style = \"padding:5px; border:1px black solid;\">".$zbrodnia['opis']."<br>".$zbrodnia['autor'].", dnia ".$zbrodnia['data']."</div></a><br>";
		}
	echo "<br><hr>";
	echo "<div id = \"stopka\">Dodał: ".$miejsce['autor']." <span style=\"float:right;\">dnia ";
	echo $miejsce['data']."</span></div><br>";
	
	
	
?>