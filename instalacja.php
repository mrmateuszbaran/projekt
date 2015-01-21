<h1>Instalator aplikacji</h1>
<br>
<?php
	if (isset($_POST['konfiguracja-baz-save']))
	{
		file_put_contents("baza.conf", $_POST['konfiguracja-baz']);
		header("Location: /");
	}
	if (isset($_POST['konfiguracja-kont-save']))
	{
		$konta = explode("\r\n\r\n", $_POST['konfiguracja-kont']);
		$bd->wykonajZapytanie($_SESSION['baza'], "TRUNCATE TABLE konta");
		echo "<hr><h3> Wygenerowane hasła: </h3>";
		foreach($konta as $x => $konto)
		{
			$linie = explode("\n", $konto);
			$imie = explode(": ", $linie[0])[1];
			$nazwisko = explode(": ", $linie[1])[1];
			$pesel = explode(": ", $linie[2])[1];
			$nrodznaki = explode(": ", $linie[3])[1];
			$poziom = explode(": ", $linie[4])[1];
			$charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			$haslo = '';
			$length = 10;
			$count = strlen($charset);
			while ($length--) {
				$haslo .= $charset[mt_rand(0, $count-1)];
			}
			echo $imie." ".$nazwisko." - ".$haslo;
			$bd->dodajKonto($_SESSION['baza'], $imie, $nazwisko, $pesel, $nrodznaki, $poziom, $haslo);
		}
		echo "<br><br><hr><script>pokaz_powodzenie(\"Konta użytkowników zapisane poprawnie!\")</script>";
	} 
	
	if (isset($_POST['konfiguracja-komenda']))
	{
		$_SESSION['baza'] = $_POST['konfiguracja-komenda'];
		header("Location: /");
	}
?>
<div id = "instalacja-div-1">
Witaj w kartotece!<br>
Ten prosty skrypt instalacyjny poprowadzi Cię przez proces podstawowej konfiguracji połączeń z bazami danych i kont użytkowników.<br>
Na początek wybierz swoją komendę. Jeśli nie masz żadnego wyboru, przejdź dalej i skonfiguruj bazy danych <br><br>
<form method = "POST" onChange = "this.submit();">
<select name = "konfiguracja-komenda">
<?php
	if (!isset($_SESSION['baza']))
	{
		$_SESSION['baza'] = $bd->pobierzListe[0];
	}
	foreach($bd->pobierzListe() as $idx => $baza)
	{
		if ($_SESSION['baza'] == $baza)
			echo "<option value = \"".$baza."\" selected>".$baza."</option>";
		else
			echo "<option value = \"".$baza."\">".$baza."</option>";
	}
?>
</select>
</form>
<br>
<img title = "Następny krok" onClick = "instalacja_nastepny(this.parentNode);" src = "res/ikonki/next.png" style = "cursor: pointer;"><br>
</div>
<div id = "instalacja-div-2">
Pierwszym krokiem który należy wykonać w tym procesie jest konfiguracja połączeń bazodanowych.<br>
Upewnij się, że konfiguracja zawiera bazę danych twojej komendy i została ona zweryfikowana poprawnie.<br>
<?php
	if (file_exists("baza.conf"))
	{
		include "konfiguracja_bazy.php";
	} else
	{
		echo "Sprawdź czy na serwerze istnieje plik \"baza.conf\"!";
	}

?>
<br>
<img title = "Poprzedni krok" onClick = "instalacja_poprzedni(this.parentNode);" src = "res/ikonki/prev.png" style = "cursor: pointer;">
<img title = "Następny krok" onClick = "instalacja_nastepny(this.parentNode);" src = "res/ikonki/next.png" style = "cursor: pointer;"><br>
</div>
<div id = "instalacja-div-3">
Teraz możesz skonfigurować konta użytkowników.<br>
<?php
	include "konfiguracja_kont.php";
?>
<br>
<img title = "Poprzedni krok" onClick = "instalacja_poprzedni(this.parentNode);" src = "res/ikonki/prev.png" style = "cursor: pointer;">
<img title = "Następny krok" onClick = "instalacja_nastepny(this.parentNode);" src = "res/ikonki/next.png" style = "cursor: pointer;"><br>
</div>
<div id = "instalacja-div-4">
Ostatni krok to utworzenie konta komendanta, czyli osoby posiadającej najwięcej uprawnień w systemie kartoteki.<br>
W tym celu wprowadź swój numer odznaki, dane osobowe, PESEL oraz pożądane hasło.<br>
Dane te zostaną zapisane w bazie danych twojej komendy, a ty będziesz jedyną osobą znającą swoje hasło.<br>
To bardzo ważne, aby nie udostępniać nikomu swojego hasła, ze względów bezpieczeństwa.<br>

<form method = "POST" name = "konfiguracja-komendanta-form" class = "konfiguracja-form" onChange = "sprawdz_formularz(this)" action = "koniec_instalacji.php">
<span>Imie: </span><input value = ""><br>
<span>Nazwisko: </span><input value = ""><br>
<span>PESEL: </span><input value = ""><br>
<span>Nr odznaki: </span><input value = ""><br>
<span>Hasło: </span><input type = "password" value = ""><br><br>

<br>
<img title = "Poprzedni krok" onClick = "instalacja_poprzedni(this.parentNode.parentNode);" src = "res/ikonki/prev.png" style = "cursor:pointer;">
<input type = "submit" value = "" style = "cursor:pointer; background:none; border:0px; background-image:url('res/ikonki/ok_big.png'); width:32px; height:32px;" title = "Poprzedni krok">
</form>
</div>
