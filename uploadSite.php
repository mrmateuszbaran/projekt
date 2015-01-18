<?php		
	
	echo "<form method = \"POST\" enctype=\"multipart/form-data\" style = \"margin-top: -10px; color: white;\">";
	echo "<input type=\"file\" multiple name=\"uploadFile[]\" id = \"uploadFiles\" accept=\"image/jpeg,image/gif,image/png\" onChange = \" checkFilesCount(); \"><br><br>";
	echo "<span id = \"uploadDontSearch\"><input type=\"checkbox\" name=\"uploadDontSearch\">Szukaj podobnych</span>";
	echo "<input type=\"submit\" name=\"uploadSubmit\" value=\"Wyślij Pliki\">";
	echo "</form>";
	
?>