<?php
	echo "<h1>Aktualności</h1>";
	
	$na_stronie = 10;
	
	if (!isset($_GET['start']))
		$od = 0;
	else 
		$od = $_GET['start'];
	$ilosc = $bd->ileDanych("news");
	$wynik = $bd->pobierzDane("*", "news", "*", null, "data", "DESC", $od, $na_stronie);
	
	foreach($wynik as $id => $news)
	{	
		echo "<div class = \"news-div\">";
		echo "<div class = \"news-div-baza\">".$news['baza']."</div>";
		echo "<div class = \"news-div-tytul\">".mysql_real_escape_string($news['tytul'])."</div>";
		echo "<div class = \"news-div-tresc\">".str_replace("\\r\\n", "<br>", mysql_real_escape_string($news['tresc']))."</div>";
		echo "<div class = \"news-div-data\">".$news['data']."</div>";
		echo "<div class = \"news-div-autor\">".$news['autor']."</div>";
		echo "</div>";
		echo "<br>";
	}
	
	if ($ilosc > $na_stronie)
	{
		for ($i = 0; $i < $ilosc/$na_stronie; $i++)
			echo "<a class = \"a-strona\" href = \"?start=".($na_stronie*$i)."\">".($i+1)."</a>";
	}
	
?>
	