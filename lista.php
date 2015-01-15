<?php

echo "Lista obrazow: <br><br>";
echo "<form name = \"image-delete-form\" method = \"POST\">";
$zapytanie = mysql_query("SELECT * FROM obrazy") or die(mysql_error());

if (isset($_SESSION['szukanyObraz']))
{
	$szukany = mysql_query("SELECT * FROM obrazy WHERE Nazwa = '$_SESSION[szukanyObraz]'");
	$szukanyHash = mysql_fetch_array($szukany);
	$szukanyHash = $szukanyHash['Hash'];
	//echo $_SESSION['szukanyObraz'];
}

function hamming($pierwszy, $drugi)
{
	$podob = 0;
	for ($i = 0; $i < 16; $i++)
	{
		if ($pierwszy[$i] == $drugi[$i])
			$podob += (100 / 16.0);
		else if (abs($pierwszy[$i] - $drugi[$i]) == 1)
			$podob += (100 / 20.0);
		else if (abs($pierwszy[$i] - $drugi[$i]) == 2)
			$podob += (100 / 24.0);
		else if (abs($pierwszy[$i] - $drugi[$i]) == 3)
			$podob += (100 / 32.0);
	}
	return round($podob, 2);
}

while($wynik = mysql_fetch_array($zapytanie))
{
	$new_width = 120;
	$new_height = 90;
	$new_AR = $new_width / $new_height;

	$imageData = imagecreatefromstring($wynik['Dane']);
	
	$old_width = imageSX($imageData);
	$old_height = imageSY($imageData);
	$old_AR = $old_width / $old_height;
	
	if($old_AR > $new_AR) 
	{
		$w = $old_height * $new_AR;
		$h = $old_height;
		$x = ($old_width - $w) / 2;
		$y = 0;
	}
	if($old_AR < $new_AR) 
	{
		$w = $old_width;
		$h = $old_width / $new_AR;
		$x = 0;
		$y = ($old_height - $h) / 2;
	}
	if($old_AR == $new_AR) 
	{
		$w = $old_width;
		$h = $old_height;
		$x = 0;
		$y = 0;
	}
	
	$thumbData = imagecreatetruecolor($new_width, $new_height);
	imagecopyresampled($thumbData, $imageData, 0, 0, $x, $y, $new_width, $new_height, $w, $h);
	
	ob_start();
	imagejpeg($thumbData);
	$data[] = ob_get_clean();
	$uri = "data:image/jpeg;base64,".base64_encode($data[count($data)-1]);
	echo "<input type = \"checkbox\" name = \"image-delete-selected[]\" value = \"".$wynik['Nazwa']."\" style = \"height: 80px;\" onChange = \"document.getElementById('icon-select-all').style.background = 'url(\'/res/ikonki/select-all-reserved.png\')';\">";
	echo "<img class = 'image-thumbnail' id = '".$wynik['Nazwa']."' onClick = 'show_image(this);' src = ".$uri." onMouseOver = 'show_tooltip(this);'>";
	
	if (isset($_SESSION['szukanyObraz']))
	{
		if ($_SESSION['szukanyObraz'] != $wynik['Nazwa'])
		{
			$podobienstwo = hamming($wynik['Hash'], $szukanyHash);
			$lista[] = array ('Obraz' => $wynik, 'Podob' => $podobienstwo);
			echo "Podobieństwo: ".$podobienstwo."%.<br>";
		} else
			echo "Szukany obraz.<br>";
	} else
		echo "<br>";
	imagedestroy($imageData);
	imagedestroy($thumbData);
}

function podobSort($item1,$item2)
{
	if ($item1['Podob'] == $item2['Podob']) return 0;
	return ($item1['Podob'] < $item2['Podob']) ? 1 : -1;
}

if (isset($_SESSION['szukanyObraz']))
{
	usort($lista, "podobSort");
	foreach ($lista as $index => $element)
	{
		echo $index.". ".$element['Obraz']['Nazwa']." => ".$element['Podob']. "%<br>";
		// $uri = "data:image/jpeg;base64,".base64_encode($data[$index]);
		// echo "<input type = \"checkbox\" name = \"image-delete-selected[]\" value = \"".$lista[$index]['Obraz']['Nazwa']."\" style = \"height: 80px;\">";
		// echo "<img class = 'image-thumbnail' id = '".$lista[$index]['Obraz']['Nazwa']."' onClick = 'show_image(this);' src = ".$uri." onMouseOver = 'show_tooltip(this);'><br>";
		
		// if ($szukany['Nazwa'] != $obraz['Obraz']['Nazwa'])
			// echo $obraz['Obraz']['Nazwa']." Podobieństwo: ".$obraz['Podob']."<br>";
		// else
			// echo $obraz['Obraz']['Nazwa']." Obraz szukany<br>";
	}
}

echo "<br><div id = \"icon-select-all\" title = \"Zaznacz wszystko\" onClick = \"toggleSelectAll(this, this.parentNode);\"></div>";
echo "<input type = \"submit\" name = \"image-delete-submit\" value = \"\"  id = \"icon-delete\" title = \"Skasuj wybrane\">";
echo "</form>";

?>