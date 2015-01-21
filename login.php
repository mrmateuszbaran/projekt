<?php 
	session_start();
	if (!isset($_SESSION['init']))
	{
		session_regenerate_id();
		$_SESSION['init'] = $_SERVER['REMOTE_ADDR'];
	}
	
	if ($_SESSION['init'] != $_SERVER['REMOTE_ADDR'])
	{
		die("Niepoprawna sesja!");
	}
	
	//include 'globals.php';
?>
<style type = "text/css">
<!--

:focus {outline:none;}
::-moz-focus-inner {border:0;}

.przycisk 
{
	width: 80px;
	height: 30px;
	
	background: #FEFEFE;
	background: -moz-linear-gradient(-45deg,  #FEFEFE 0%, #DCDCDC 51%, #CDCDCD 100%);
	background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,#FEFEFE), color-stop(51%,#DCDCDC), color-stop(100%,#CDCDCD));
	background: -webkit-linear-gradient(-45deg,  #FEFEFE 0%,#DCDCDC 51%,#CDCDCD 100%);
	background: -o-linear-gradient(-45deg,  #FEFEFE 0%,#DCDCDC 51%,#CDCDCD 100%);
	background: -ms-linear-gradient(-45deg,  #FEFEFE 0%,#DCDCDC 51%,#CDCDCD 100%);
	background: linear-gradient(135deg,  #FEFEFE 0%,#DCDCDC 51%,#CDCDCD 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#FEFEFE', endColorstr='#CDCDCD',GradientType=1 );

	border: 2px outset black;
	text-shadow: 0px 0px 5px white;
	border-radius: 5px;
	color: black;
	margin: 5px 0px 5px 0px;
}
.przycisk:hover
{
	border: 2px outset orange; 
	
}
.przycisk:active
{
	text-decoration: none;
	border: 2px inset black; 
	outline: none !important;
}

.combo-div
{
	width: auto;
	border: 1px black solid;
}

.combo
{
	width: auto;
	overflow: hidden;
	
	border: 1px outset white;
	border-radius: 5px;
	box-shadow: 0px 0px 2px black;
    x-webkit-appearance: none;
    x-moz-appearance: none;
    xappearance: none;
	margin: 5px 0px 5px 0px;
	
	background: #FEFEFE;
	background: -moz-linear-gradient(-45deg,  #FEFEFE 0%, #DCDCDC 51%, #CDCDCD 100%);
	background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,#FEFEFE), color-stop(51%,#DCDCDC), color-stop(100%,#CDCDCD));
	background: -webkit-linear-gradient(-45deg,  #FEFEFE 0%,#DCDCDC 51%,#CDCDCD 100%);
	background: -o-linear-gradient(-45deg,  #FEFEFE 0%,#DCDCDC 51%,#CDCDCD 100%);
	background: -ms-linear-gradient(-45deg,  #FEFEFE 0%,#DCDCDC 51%,#CDCDCD 100%);
	background: linear-gradient(135deg,  #FEFEFE 0%,#DCDCDC 51%,#CC 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#FEFEFE', endColorstr='#CDCDCD',GradientType=1 );
}

.combo:focus
{
	border: 1px inset white;
	xoutline: 2px solid #49aff2;
	xoutline: 2px solid -webkit-focus-ring-color;
	xoutline-offset: -5px;
}

.combo > option {
  margin: 3px;
  padding: 5px;
  text-shadow: none;
  background: #f2f2f2;
  border-radius: 3px;
  cursor: pointer;
}

-->
</style>

	<?php
		// if dane z POST zapisane w SESJI (jeśli zalogowany) - nieprzewidywana sytuacja!
		// sprawdz i aktualizuj
		$lista = $bd->pobierzListeKont();
	?>
	
	<div id = "login-div" align = "center">
		<br>
		<div>
			<form name = "formularz_logowania" method = "POST" action = "">
			<select class = "combo" name = "baza">
			<?php
				foreach ($lista as $id => $element)
				{
					echo "<option value = \"".$element."\"> ".$element." </option>";
				}
			?>
			</select><br>
			<span style = "width:120px; display:inline-block;" >Numer odznaki: </span><input type = "text" name = "nrodznaki"><br>
			<span style = "width:120px; display:inline-block;">Hasło: </span><input type = "password" name = "haslo"><br><br>
			<input class = "przycisk" type = "submit" name = "wyslij_logowanie" value = "Zaloguj">
		</form>
		</div>
	</div>
	
	<?php 
		if (isset($_POST['wyslij_logowanie']))
		{
			echo "<hr>";
			$wynik = $bd->wykonajZapytanie($_POST['baza'], "select * from konta where nrodznaki = '".$_POST['nrodznaki']."' and haslomd5 = md5('".$_POST['haslo']."')", true);
			if ($wynik && count($wynik) > 0)
			{
				$user = new Uzytkownik($_POST['nrodznaki'], $wynik['poziom'], $_POST['baza']);
				$_SESSION['user'] = $user;
				header("Location: /");
			} else
				echo "<h3 align = \"center\"><font color = \"red\">Logowanie nie powiodło się!</font></h3>";
		}
	?>

<?php
	//session_destroy();
?>