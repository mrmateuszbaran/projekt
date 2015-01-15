<?php
	session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel = "stylesheet" type = "text/css" href = "styl.css">
	<script src = "funkcje.js" type = "text/javascript"></script>
	<title>Projekcik</title>
</head>

<body>

<div id = "kontener">
	<div id = "logo">
	</div>
	<div id = "menu">
		<ul id = "menu_lista">
			<li><a href = "#" id = "link-menu-home"><div style = "width:180px; height:30px; display:inline-block;"></div></a></li>
			<li><a href = "#" id = "link-menu-miejsca"><div style = "width:180px; height:30px; display:inline-block;"></div></a></li>
			<li><a href = "#" id = "link-menu-podejrzani"><div style = "width:175px; height:30px; display:inline-block;"></div></a></li>
			<li><a href = "#" id = "link-menu-onas"><div style = "width:175px; height:30px; display:inline-block;"></div></a></li>
		</ul>
	</div>
	<div id = "tresc">
		<?php 
			include "obrazy.php";
		?>
	</div>
</div> <!-- KONTENER -->

</body>

</html>
<?php
	session_destroy();
?>