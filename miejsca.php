<span id = "upload-box-link" onClick = "toggle_upload_box();"> Wyślij nowe zdjęcie </span>
<div id = "upload-box" name = "upload-box">
<?php 
	if (isset($_POST['uploadSubmit']))
	{
		// TODO:
		// Check if upload success
		include "upload.php";
		if (isset($_POST['uploadDontSearch']) && count($_FILES["uploadFile"]["name"]) == 1)
		{
			$_SESSION['szukanyObraz'] = $_FILES["uploadFile"]["name"][0];
			//header("Location: /search.php?id=".$_FILES['uploadFile']['name'][0]."");
		} 
		header("Location: ?strona=miejsca");
		exit();
	}
	if (isset($_SESSION['upload']))
	{
		if ($_SESSION['upload'] == "OK")
		{
			echo "<h2>Pomyślnie wgrano pliki.</h2><br>";
		} else
		{
			echo $_SESSION['upload'];	// error
		}
		unset($_SESSION['upload']);
	}
	include "uploadSite.php";
	include "funkcje.php";
?>
</div>

<?php
	
	$komenda = "*";
	if (isset($_POST['komenda']))
	{
		$komenda = $_POST['komenda'];
	}
	$miejsca = $bd->pobierzDane($komenda, "miejsca");
	
	echo "<h1>Miejsca zbrodni</h1>";
	echo "<form name = \"image-delete-form\" method = \"POST\">";
	foreach($miejsca as $idx => $miejsce)
	{
		if (isset($miejsce['obrazy'][0]))
		{
			$uri = generujMiniature($miejsce['obrazy'][0]['dane']);
		} else
		{
			echo "Brak obrazu!";
		}
		echo "<input type = \"checkbox\" name = \"image-delete-selected[]\" value = \"".$miejsce['id']."\" style = \"height: 80px;\">";
		echo "<img class = 'image-thumbnail' id = '".$miejsce['obrazy'][0]['id']."' baza = '".$miejsce['baza']."' onMouseOut = \"toggle_tooltip(this);\" onMouseOver = \"toggle_tooltip(this);\" onClick = 'pokaz_obraz(this);' src = ".$uri.">";
		
		echo "<div class = \"miejsce-div\">";
		echo "<div class = \"miejsce-div-baza\">".$miejsce['baza']."</div>";
		echo "<div class = \"miejsce-div-adres\">".str_replace("\\r\\n", "<br>", mysql_real_escape_string($miejsce['adres']))."</div>";
		echo "<div class = \"miejsce-div-autor\">".$miejsce['autor']."</div>";
		echo "<div class = \"miejsce-div-data\">".$miejsce['data']."</div>";
		echo "</div>";
		
		echo "<br><div id = \"icon-select-all\" title = \"Zaznacz wszystko\" onClick = \"toggleSelectAll(this, this.parentNode);\"></div>";
		echo "<input type = \"submit\" name = \"image-delete-submit\" value = \"\"  id = \"icon-delete\" title = \"Skasuj wybrane\">";
		echo "</form>";
	}
?>