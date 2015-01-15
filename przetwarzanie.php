<?php

	//header("Content-type: image/jpeg");
	function generateHashFromImage($image)
	{
		$W = 8;
		$H = 8;
		$imageSmall = imagecreate($W, $H);
		$imageFinal = imagecreate($W, $H);
		$width = imagesx($image);
		$height = imagesy($image);
		
		imagecopyresized($imageSmall, $image, 0, 0, 0, 0, $W, $H, $width, $height);
		$totalGray = 0;
		for ($i = 0; $i < $H; $i++)
		{
			for ($j = 0; $j < $W; $j++)
			{
				$rgb = imagecolorat($imageSmall, $j, $i);
				$cols = imagecolorsforindex($imageSmall, $rgb);
				$r = $cols['red'];
				$g = $cols['green'];
				$b = $cols['blue'];
				$gray = ($r + $g + $b) / 3;
				$totalGray += $gray;
			}
		}
		$meanGray = $totalGray / ($W * $H);
		
		$black = imagecolorallocate($imageFinal, 0, 0, 0);
		$white = imagecolorallocate($imageFinal, 255, 255, 255);
		
		for ($i = 0; $i < $H; $i++)
		{
			for ($j = 0; $j < $W; $j++)
			{
				$rgb = imagecolorat($imageSmall, $j, $i);
				$cols = imagecolorsforindex($imageSmall, $rgb);
				$r = $cols['red'];
				$g = $cols['green'];
				$b = $cols['blue'];
				$gray = ($r + $g + $b) / 3;
				if ($gray >= $meanGray)
				{
					$binary .= 1;
					imagesetpixel($imageFinal, $j, $i, $white);
				}
				else
				{
					$binary .= 0;
					imagesetpixel($imageFinal, $j, $i, $black);
				}
			}
		}
		$binLO = substr($binary, 0, strlen($binary) / 2);
		$binHI = substr($binary, strlen($binary) / 2, strlen($binary) / 2);
		
		//echo $binLO. "&nbsp;" .$binHI. "<br>";
		$hexLO = dechex(bindec($binLO));
		$hexLO = str_repeat("0", floor(strspn($binLO, "0") / 4)).$hexLO;
		$hexHI = dechex(bindec($binHI));
		$hexHI = str_repeat("0", floor(strspn($binHI, "0") / 4)).$hexHI;
		$hex = $hexLO.$hexHI;
		
		//echo $binary;
		//echo imagejpeg($imageFinal);
		
		imagedestroy($image);
		imagedestroy($imageSmall);
		imagedestroy($imageFinal);
		return $hex;
	}
	
	//generateHashFromImage(imagecreatefromjpeg("D:/Foto/20130302308.jpg"));
	//echo generateHashFromImage(imagecreatefromjpeg("res/logo.jpg"));
	// exit();
	
	// $W = 8;
	// $H = 8;
	// $timer = microtime(true);
	// $imgPath = "res/logo.jpg";
	// $img = imagecreatefromjpeg($imgPath);
	// $img2 = imagecreate($W, $H);
	// $imgFinal = imagecreate($W, $H);

	// list($width, $height) = getimagesize($imgPath);

	// imagecopyresized($img2, $img, 0, 0, 0, 0, $W, $H, $width, $height);

	// $total = 0;
	
	// for ($i = 0; $i < $H; $i++)
	// {
		// for ($j = 0; $j < $W; $j++)
		// {
			// $rgb = imagecolorat($img2, $j, $i);
			// $cols = imagecolorsforindex($img2, $rgb);
			// $r = $cols['red'];
			// $g = $cols['green'];
			// $b = $cols['blue'];
			// $gray = ($r + $g + $b) / 3;
			// $total += $gray;
		// }
	// }
	
	// $mean = $total / ($W * $H);
	// echo $mean."<br>";
	
	// $black = imagecolorallocate($imgFinal, 0, 0, 0);
	// $white = imagecolorallocate($imgFinal, 255, 255, 255);
	// for ($i = 0; $i < $H; $i++)
	// {
		// for ($j = 0; $j < $W; $j++)
		// {
			// $rgb = imagecolorat($img2, $j, $i);
			// $cols = imagecolorsforindex($img2, $rgb);
			// $r = $cols['red'];
			// $g = $cols['green'];
			// $b = $cols['blue'];
			// $gray = ($r + $g + $b) / 3;
			// if ($gray >= $mean)
			// {
				// $binary .= 1;
				// imagesetpixel($imgFinal, $j, $i, $white);
			// }
			// else
			// {
				// $binary .= 0;
				// imagesetpixel($imgFinal, $j, $i, $black);
			// }
		// }
	// }
	// $binLO = substr($binary, strlen($binary) / 2);
	// $binHI = substr($binary, -strlen($binary) / 2);
	
	// echo $binLO. "&nbsp;" .$binHI. "<br>";
	// $hexLO = dechex(bindec($binLO));
	// $hexLO = str_repeat("0", floor(strspn($binLO, "0") / 4)).$hexLO;
	// $hexHI = dechex(bindec($binHI));
	// $hexHI = str_repeat("0", floor(strspn($binHI, "0") / 4)).$hexHI;
	// $hex = $hexLO.$hexHI;
	// print('<br>'.$hexLO.$hexHI.'<br>');
	// file_put_contents("pliczek.txt", $hex);

	// echo imagejpeg($imgFinal);
	// echo "<br>Czas dzia³ania: " . (microtime(true) - $timer) . " mikrosekund";

	
	// imagedestroy($img);
	// imagedestroy($img2);
	// imagedestroy($imgFinal);
?>