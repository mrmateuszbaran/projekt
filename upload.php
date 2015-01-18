<?php
	if (isset($_POST['uploadSubmit']))
	{
		ini_set('default_socket_timeout', 900); // 900 sekund = 15 minut
	}
?>

<?php
	//include "globals.php";
	
	$included = false;	// if przetwarzanie included
	
	for ($i = 0; $i < count($_FILES["uploadFile"]["name"]); $i++)
	{
		$target_dir = "obrazy/";
		$target_dir = $target_dir . basename( $_FILES["uploadFile"]["name"][$i]);		// TODO: wiele plikow na raz
		$uploadOk = 1;
		$nazwaPliku = basename( $_FILES["uploadFile"]["name"][$i]);

		$ext = explode('.', $nazwaPliku);
		$ext = $ext[count($ext)-1];
		$ext = strtolower($ext);
		echo "Rozszerzenie: " . $ext . "<br />";
		$rozszerzenia = array("jpg", "gif", "png");
		if (!in_array($ext, $rozszerzenia))
		{
			echo "Przepraszamy, tylko pliki .jpg, .gif, .png!<br>";
			$uploadOk = 0;
		}
		
		if ($uploadOk)
		{
			$content = mysql_real_escape_string(file_get_contents ($_FILES["uploadFile"]["tmp_name"][$i]));
			if (!$included)
			{
				include "przetwarzanie.php";
				$included = true;
			}
			$image = imagecreatefromstring(file_get_contents ($_FILES["uploadFile"]["tmp_name"][$i]));
			$data = date('Y-m-d H:i:s');
			$hash = generateHashFromImage($image);
			$dctHash = generateDCTHashFromImage($image);
			$bd->wykonajZapytanie("Kraków", "INSERT INTO obrazy(data, hashAvg, hashDCT, dane) VALUES ('$data','$hash', '$dctHash', '$content')");
			$_SESSION['upload'] = "OK";
			imagedestroy($image);
		} else
		{
			echo "Plik nie został załadowany...<br>";
		}
	}	
?>