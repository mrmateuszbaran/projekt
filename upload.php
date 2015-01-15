<?php
		
	$polaczenie = mysql_connect('mysql.hostinger.pl', 'u322806910_wieik', 'info3pk');
	mysql_select_db('u322806910_wieik');
	
	$included = false;	// if przetwarzanie included
	
	for ($i = 0; $i < count($_FILES["uploadFile"]["name"]); $i++)
	{
		$target_dir = "obrazy/";
		$target_dir = $target_dir . basename( $_FILES["uploadFile"]["name"][$i]);		// TODO: wiele plikow na raz
		$uploadOk = 1;
		$nazwaPliku = basename( $_FILES["uploadFile"]["name"][$i]);

		//echo "Wybrano plik: ".basename( $_FILES["uploadFile"]["name"])."<br>";
		
		$czy_istnieje = mysql_num_rows(mysql_query("SELECT * FROM obrazy WHERE Nazwa = '$nazwaPliku'"));
		if ($czy_istnieje != 0) {
			die("Plik już istnieje!<br>");
			$uploadOk = 0;
		} 

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
			$hash = generateHashFromImage($image);
			mysql_query("INSERT INTO obrazy(Nazwa, Autor, Hash, Dane) VALUES ('$nazwaPliku','Mateusz','$hash', '$content')") or die("Błąd przesyłania pliku, prawdopodobnie zbyt duży rozmiar - maksymalnie 1MB!");
			$_SESSION['upload'] = "OK";
			// if (move_uploaded_file($_FILES["uploadFile"]["tmp_name"], $target_dir)) 
			// {
				// echo "Plik ". basename( $_FILES["uploadFile"]["name"]). " został poprawnie załadowany.<br>";
				
				// $content = mysql_real_escape_string(file_get_contents ($target_dir));
				// $exif = exif_read_data($target_dir, 'IFD0');
				// if ($exif)
					// var_dump($exif);
				// else 
					// echo "Brak meta-danych!";

				// mysql_query("INSERT INTO obrazy(Nazwa, Autor, Hash, Dane) VALUES ('$nazwaPliku','Mateusz','HASZTEMP', '$content')") or print(mysql_error());
				
			// } else {
				// echo "Błąd wgrywania pliku!<br>";
			// }		
		} else
		{
			echo "Plik nie został załadowany...<br>";
		}
	}
	
	mysql_close($polaczenie);	
?>