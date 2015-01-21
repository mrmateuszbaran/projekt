<?php 
echo "<div id = \"konfiguracja-kont-div\" style = \"border: 2px black solid; padding: 5px;\">";
$wynik = $bd->pobierzDane($_SESSION['baza'], "konta");

foreach ($wynik as $idx => $konto)
{
	echo "<form method = \"POST\" class = \"konfiguracja-form\" name = \"konfiguracja-kont-form\" onChange = \"sprawdz_formularz(this)\">";
	echo "<span>Imie: </span><input value = \"".$konto['imie']."\"><br>";
	echo "<span>Nazwisko: </span><input value = \"".$konto['nazwisko']."\"><br>";
	echo "<span>PESEL: </span><input value = \"".$konto['pesel']."\"><br>";
	echo "<span>Nr odznaki: </span><input value = \"".$konto['nrodznaki']."\"><br>";
	echo "<span>Poziom: </span><input value = \"".$konto['poziom']."\"><br><br>";
	echo "<input type = \"button\" style = \"width:16px; height:16px; display:inline-block; cursor:pointer; border:0px; background:none; background-image:url(res/ikonki/delete.png)\" onClick = \"usun_konto(this);\"><br>";
	echo "</form>";
}
echo "<form method = \"POST\" action = \"\" name = \"zapis-kont\">";
echo "<input type = \"hidden\" name = \"konfiguracja-kont\">";
echo "<br><input type = \"button\" style = \"width:16px; height:16px; display:inline-block; cursor:pointer; border:0px;  background:none; background-image:url(res/ikonki/add.png)\" onClick = \"dodaj_konto(this);\"><br><br>";
echo "<input type = \"submit\" id = \"konfiguracja-kont-save\" name = \"konfiguracja-kont-save\" value = \" \" style = \"width:16px; height:16px; display:inline-block; cursor:pointer; border:0px; padding:0px; background:none; background-image:url(res/ikonki/save.png)\" onClick = \"zapisz_konta(this); \"><br>";
echo "</form>";
echo "</div>";

?>