<?php

class Polaczenie
{
	public $system;
	public $typ;
	public $conn;
	
	function __construct($system, $typ, $conn)
	{
		$this->system = $system;
		$this->typ = $typ;
		$this->conn = $conn;
	}
}

class BazaDanych
{
	private $polaczenia = array();
//	private $polaczeniaMySQL = array();
//	private $polaczeniePostgreSQL = array();
	
	
	function __construct()
	{
		$plik = fopen("baza.conf", "r") or die("Błąd wczytywaniu pliku konfiguracyjnego!");
		$baza = "";
		fread($plik, 3);	// 3 znaki identyfikator UTF-8!
		while (($linia = fgets($plik)) !== false) 
		{
			if ($linia != "\r\n" && $linia != feof($plik))
			{
				$baza .= $linia;
			}
			else
			{
				if ($linia == feof($plik))
					$baza .= $linia;
				$this->dodajPolaczenie($baza);
				$baza = "";
			}
		}
		fclose($plik);
	}
	
	function __destruct()
	{
		foreach($this->polaczenia as $id => $polaczenie)
		{
			if ($polaczenie->typ == "MySQL")
			{
				mysql_close($polaczenie->conn);
			} else if ($polaczenie->typ == "PostgreSQL")
			{
				pg_close($polaczenie->conn);
			}
		}
	}
	
	function dodajPolaczenie($baza)
	{
		$baza = explode("\r\n", $baza);
		
		$nazwa = trim(substr($baza[0], 3));
		$typ = strtolower(trim(substr($baza[1], 5)));
		$system = trim(substr($baza[2], 8));
		$bd = trim(substr($baza[3], 6));
		$host = trim(substr($baza[4], 6));
		$user = trim(substr($baza[5], 6));
		$pass = trim(substr($baza[6], 6));
		/*
		echo "Nazwa: ".$nazwa."<br>";
		echo "Typ: ".$typ."<br>";
		echo "System: ".$system."<br>";
		echo "Baza: ".$bd."<br>";
		echo "Host: ".$host."<br>";
		echo "User: ".$user."<br>";
		echo "Pass: ".$pass."<br>";
		*/
		if ($system == "MySQL")
		{
//			echo "Tworzenie połączenia MySQL.<br>";
			$pol = mysql_connect($host, $user, $pass);
			if ($pol)
				$this->polaczenia[$nazwa] = new Polaczenie($system, $typ, $pol);
			else 
				echo "Błąd połączenia MySQL!<br>";
			mysql_select_db($bd, $pol);
		} else if ($system == "PostgreSQL")
		{
//			echo "Tworzenie połączenia PostgreSQL.<br>";
			$conn_string = "host=".$host." port=5432 dbname=".$bd." user=".$user." password=".$pass."";
			$pol = pg_connect($conn_string, PGSQL_CONNECT_FORCE_NEW);
			if ($pol)
				$this->polaczenia[$nazwa] = new Polaczenie($system, $typ, $pol);
			else 
				echo "Błąd połączenia PostgreSQL!<br>";
		}
	}
	
	function pobierzListe()
	{
		return array_keys($this->polaczenia);
	}
	
	function pobierzListeKont()
	{
		$wynik = array();
		foreach ($this->polaczenia as $id => $polaczenie)
		{
			if (in_array("uzytkownicy", explode(",", $polaczenie->typ)))
				$wynik[] = $id;
		}
		return $wynik;
	}
	
	function pobierzListeNews()
	{
		$wynik = array();
		foreach ($this->polaczenia as $id => $polaczenie)
		{
			if (in_array("news", explode(",", $polaczenie->typ)))
				$wynik[] = $id;
		}
		return $wynik;
	}
	
	
	function wykonajZapytanie($baza, $zap)
	{		
		$baza = $this->polaczenia[$baza];
		
		if ($baza->system == "MySQL")
			$zapytanie = mysql_query($zap, $baza->conn);
		else
			$zapytanie = pg_query($baza->conn, $zap);
			
		if (!$zapytanie)
		{
			echo "Błąd zapytania!<br>";
			return null;
		}
				
		//if ($baza->system == "MySQL")
		//{
		//	return mysql_fetch_assoc($zapytanie);
		//}
		//else
		//{
		//	return pg_fetch_assoc($zapytanie);
		//}
	}
	
	function ileDanych($tabela)
	{
		$w = 0;
		foreach ($this->polaczenia as $idx => $polaczenie)
		{
			if ((strtolower($tabela) == "news" && !in_array("news", explode(",", $polaczenie->typ))) ||
				(strtolower($tabela) == "obrazy" && !in_array("dane", explode(",", $polaczenie->typ))))
			{
				continue;
			}
			$zap = "SELECT count(*) FROM ".$tabela;
			if ($polaczenie->system == "MySQL")
			{
				$zapytanie = mysql_query($zap, $polaczenie->conn);
				$w += @mysql_fetch_row($zapytanie)[0];
			}
			else
			{
				$zapytanie = pg_query($polaczenie->conn, $zap);
				$w += @pg_fetch_row($zapytanie)[0];
			}
			if (!$zapytanie)
			{
				echo "Błąd zapytania ".$polaczenie->system."!<br>";
				break;
			}
		}
		return $w;
	}
	
	function pobierzDane($bazy, $tabela, $kolumny = "*", $warunki = null, $sortKolumna = null, $sortKierunek = "ASC", $od = null, $ile = null)
	{
		// TODO
		// Pobierz obrazy!
		if ($tabela == null || $tabela == "")
		{
			echo "Podaj tabelę!<br>";
			return;
		} 

		$w = array();	
		foreach ($this->polaczenia as $idx => $polaczenie)
		{
			if ($bazy != "*" && !in_array($idx, explode(",", $bazy)))
			{
				continue;
			}
			if ((strtolower($tabela) == "news" && !in_array("news", explode(",", $polaczenie->typ))) ||
				(strtolower($tabela) == "miejsca" && !in_array("dane", explode(",", $polaczenie->typ))) ||
				(strtolower($tabela) == "podejrzani" && !in_array("dane", explode(",", $polaczenie->typ))) ||
				(strtolower($tabela) == "pojazdy" && !in_array("dane", explode(",", $polaczenie->typ))) ||
				(strtolower($tabela) == "uzytkownicy" && !in_array("uzytkownicy", explode(",", $polaczenie->typ))))
			{
				continue;
			}
			$zap = "SELECT ".$kolumny." FROM ".$tabela."";
			if (isset($sortKolumna))
				$zap .= " ORDER BY ".$sortKolumna. " ".$sortKierunek;
			if (isset($ile))
				if (isset($od))
					$zap .= " LIMIT ".$od.", ".$ile;
				else
					$zap .= " LIMIT ".$ile;
				
			if ($polaczenie->system == "MySQL")
				$zapytanie = @mysql_query($zap, $polaczenie->conn);
			else
				$zapytanie = @pg_query($polaczenie->conn, $zap);
			if (!$zapytanie)
			{
				echo "Błąd zapytania ".$polaczenie->system."!<br>";
				break;
			}
			
			while ($polaczenie->system == "MySQL" ? $wynik = mysql_fetch_assoc($zapytanie) : $wynik = pg_fetch_assoc($zapytanie))
			{
				$w[] = $wynik;
				$w[count($w)-1]['baza'] = $idx;
				if ($tabela == "miejsca" || $tabela == "podejrzani" || $tabela == "pojazdy")
				{
					//$id = miejsce.id
					//$obrazy_id = pobierzDane(obraz.id, obraz_miejsce, miejsce.id == $id);
					$w[count($w)-1]['obrazy'] = $this->pobierzDane($idx, "obrazy", "*");	// WHERE obraz.id == miejsce.id!!!! (tabela obraz_miejsce)
				}
			}
		}
		return $w;
	}
}

?>