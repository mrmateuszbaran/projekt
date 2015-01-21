<?php 
echo "<div id = \"konfiguracja-baz-div\" style = \"border: 2px black solid; padding: 5px;\">";
foreach($bd->pobierzPolaczenia() as $idx => $polaczenie)
{
	echo "<form method = \"POST\" class = \"konfiguracja-form\" name = \"konfiguracja-baz-form\" onChange = \"sprawdz_formularz(this)\">";
	echo "<span>Nazwa: </span><input value = \"".$idx."\"><br>";
	echo "<span>Typ: </span><input value = \"".$polaczenie->typ."\"><br>";
	echo "<span>System: </span><select><option value = \"".$polaczenie->system."\">".$polaczenie->system."</option>";
	$druga_opcja = $polaczenie->system == "MySQL" ? "PostgreSQL" : "MySQL";
	echo "<option value = \"".$druga_opcja."\">".$druga_opcja."</option></select><br>";
	echo "<span>Baza: </span><input value = \"".$polaczenie->db."\"><br>";
	echo "<span>Host: </span><input value = \"".$polaczenie->host."\"><br>";
	echo "<span>Uzytkownik: </span><input value = \"".$polaczenie->user."\"><br>";
	echo "<span>Hasło: </span><input type = \"password\" value = \"".$polaczenie->pass."\"><br><br><br>";
	echo "<input type = \"button\" style = \"width:16px; height:16px; display:inline-block; cursor:pointer; border:0px; background:none; background-image:url(res/ikonki/delete.png)\" onClick = \"usun_baze(this);\"><br>";
	echo "</form>";
}
echo "<form method = \"POST\" action = \"\" name = \"zapis-baz\">";
echo "<input type = \"hidden\" name = \"konfiguracja-baz\">";
echo "<br><input type = \"button\" style = \"width:16px; height:16px; display:inline-block; cursor:pointer; border:0px;  background:none; background-image:url(res/ikonki/add.png)\" onClick = \"dodaj_baze(this);\"><br><br>";
echo "<input type = \"submit\" id = \"konfiguracja-baz-save\" name = \"konfiguracja-baz-save\" value = \" \" style = \"width:16px; height:16px; display:inline-block; cursor:pointer; border:0px; padding:0px; background:none; background-image:url(res/ikonki/save.png)\" onClick = \"zapisz_bazy(this); \"><br>";
echo "</form>";
echo "</div>";

?>