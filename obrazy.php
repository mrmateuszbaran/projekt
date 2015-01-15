<div id = "obrazy" style = "padding: 20px;">

<span id = "upload-box-link" onClick = "toggle_upload_box();"> Wyślij nowe zdjęcie </span>
<div id = "upload-box" name = "upload-box">
<?php 
	include "uploadSite.php";
?>
</div>

<?php
	
	$polaczenie = mysql_connect('mysql.hostinger.pl', 'u322806910_wieik', 'info3pk');
	mysql_select_db('u322806910_wieik');
	
	if (isset($_POST['image-delete-submit']))
	{
		if (count($_POST['image-delete-selected']) > 0)
		{
			foreach ($_POST['image-delete-selected'] as $idx => $name)
			{
				mysql_query("DELETE FROM obrazy WHERE Nazwa = '$name'") or die(mysql_error());
				$_SESSION['deleted'][] = $name;
			}
		}
		header("Location: /");
		exit();
	}
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
		header("Location: /");
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
	/*function stworz_miniature($obraz, $nowa_szerokosc, $nowa_wysokosc)
	{
		
	}*/
	//header("Content-type: image/jpeg");
		
	include "lista.php";
	if (isset($_SESSION['szukanyObraz']))
	{
		unset($_SESSION['szukanyObraz']);
	}
	
	mysql_close($polaczenie);
	
?>

</div>