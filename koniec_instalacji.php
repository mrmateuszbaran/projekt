<?php

	if (file_exists("instalacja.php"))
	{
		unlink("instalacja.php");
		header("Location: /");
	} else
	{
		header("Location: /");
	}
	
?>