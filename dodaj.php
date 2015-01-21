<?php

$co = htmlspecialchars($_GET['co']);

echo "<form method = \"POST\" action = \"\" name = \"dodaj-form\" id = \"dodaj-form\">";
switch($co)
{
	case "podejrzani":
		if (isset($_POST['dodaj-form-save']))
		{
			$res = $bd->wykonajZapytanie($_SESSION['komenda'], "INSERT INTO podejrzani VALUES(0, '$_POST[imie]', '$_POST[nazwisko]', '$_POST[pesel]', '$_SESSION[user]', '".date('Y-m-d')."')",true);
			if ($res)
				echo "<script>pokaz_powodzenie(\"Pomyślnie dodano wpis!\");</script>";
			else
				echo "<script>pokaz_blad(\"Nie udało się dodać wpisu!\");</script>";
		} else
		{
			echo "<span>Imie: </span><input name = \"imie\"><br>";
			echo "<span>Nazwisko: </span><input name = \"nazwisko\"><br>";
			echo "<span>PESEL: </span><input name = \"pesel\"><br>";
			echo "<span>Dodaj zdjęcia: </span>";
			include "uploadSite.php";
		}
		break;
	case "miejsca":
		if (isset($_POST['dodaj-form-save']))
		{
			$res = $bd->wykonajZapytanie($_SESSION['komenda'], "INSERT INTO miejsca VALUES(0, '$_POST[adres]', '$_POST[opis]', '$_SESSION[user]', 'now()')",true);
			if ($res)
				echo "<script>pokaz_powodzenie(\"Pomyślnie dodano wpis!\");</script>";
			else
				echo "<script>pokaz_blad(\"Nie udało się dodać wpisu!\");</script>";
		}
		echo "<span>Adres: </span><input name = \"adres\"><br>";
		echo "<span>Opis: </span><textarea name = \"opis\"></textarea><br>";
		echo "<span>Dodaj zdjęcia: </span>";
		include "uploadSite.php";
		break;
	case "pojazdy":
		if (isset($_POST['dodaj-form-save']))
		{
		
		} else
		{
			echo "<span>Nr rejestracyjny: </span><input name = \"nrrej\"><br>";
			echo "<span>Marka: </span><input name = \"marka\"><br>";
			echo "<span>Model: </span><input name = \"model\"><br>";
			echo "<span>Nr silnika: </span><input name = \"nrsil\"><br>";
			echo "<span>Dodaj zdjęcia: </span>";
			include "uploadSite.php";
		}
		break;
}
echo "<br><br><input type = \"submit\" name = \"dodaj-form-save\" value = \" \" style = \"width:32px; height:32px; cursor:pointer; border:0px; background:url('res/ikonki/ok_big.png');\">";
echo "</form>";

?>