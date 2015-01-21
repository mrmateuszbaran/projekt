<?php

	$N = 32;
	$PI = 3.14159265358979323846;	
	
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
		
		//imagedestroy($image);
		imagedestroy($imageSmall);
		imagedestroy($imageFinal);
		return $hex;
	}
	
	function G($j, $i, $g)
	{
		global $N;
		global $PI;
		if ($j == 0)
			$Cx = 1/sqrt(2.0);
		else
			$Cx = 1;
		if ($i == 0)
			$Cy = 1/sqrt(2.0);
		else
			$Cy = 1;
			
		$sum = 0;
		for ($x = 0; $x < $N; $x++)
		{
			for ($y = 0; $y < $N; $y++)
			{
				$sum += $g[$x][$y] * cos(((2*$x+1)*$j*$PI)/(2*$N)) * cos(((2*$y+1)*$i*$PI)/(2*$N));
			}
		}
		return round(1/sqrt(2*$N) * $Cx * $Cy * $sum, 2);
	}
		
	function generateDCTHashFromImage($image)
	{
		$N = 32;
		$W = $N;
		$H = $N;
		$imageSmall = imagecreate($W, $H);
		$imageSmallGray = imagecreate($W, $H);
		$imageDCT = imagecreate($W, $H);
		$imageFinal = imagecreate(8, 8);
		$width = imagesx($image);
		$height = imagesy($image);
			
		imagefilter($image, IMG_FILTER_GRAYSCALE);
		imagefilter($image, IMG_FILTER_SMOOTH, 6);
		imagecopyresized($imageSmall, $image, 0, 0, 0, 0, $W, $H, $width, $height);
		for ($i = 0; $i < $H; $i++)
		{
			for ($j = 0; $j < $W; $j++)
			{
				$rgb = imagecolorat($imageSmall, $j, $i);
				$cols = imagecolorsforindex($imageSmall, $rgb);
				$r = $cols['red'];
				$g = $cols['green'];
				$b = $cols['blue'];
				$gray = (int)(0.21 * $r + 0.72 * $g + 0.07 * $b);
				$graytable[$j][$i] = $gray;
				$col = imagecolorallocate($imageSmallGray, $gray, $gray, $gray);
				if (!$col)
					$col = imagecolorresolve($imageSmallGray, $gray, $gray, $gray);
				imagesetpixel($imageSmallGray, $j, $i, $col);
			}
		}
		
		$total = 0;
		for ($i = 0; $i < $H; $i++)
		{
			for ($j = 0; $j < $W; $j++)
			{
				$dcttable[$j][$i] = G($j, $i, $graytable);
				$col = imagecolorallocate($imageDCT, $dcttable[$j][$i], $dcttable[$j][$i], $dcttable[$j][$i]);
				if (!$col)
					$col = imagecolorresolve($imageDCT, $dcttable[$j][$i], $dcttable[$j][$i], $dcttable[$j][$i]);
				imagesetpixel($imageDCT, $j, $i, $col);
				if ($i < 8 && $j < 8)
				{
					$total += $test[$j][$i];
				}
			}
		}
		$total -= $test[0][0];
		$mean = $total / 64.0;
			
		$DCT = "";
		$white = imagecolorallocate($imageFinal, 255, 255, 255);
		$black = imagecolorallocate($imageFinal, 0, 0, 0);
		for ($i = 0; $i < 8; $i++)
		{
			for ($j = 0; $j < 8; $j++)
			{
				if ($dcttable[$i][$j] >= $mean)
				{
					$DCT .= "1";
					imagesetpixel($imageFinal, $j, $i, $white);
				}
				else
				{
					$DCT .= "0";
					imagesetpixel($imageFinal, $j, $i, $black);
				}
			}
		}
		$hexLO = dechex(bindec(substr($DCT, 0, strlen($DCT)/2)));
		$hexHI = dechex(bindec(substr($DCT, strlen($DCT)/2, strlen($DCT)/2)));	
		$wynik = $hexLO.$hexHI;
		
		ob_start();
		imagepng($imageFinal);
		$data = ob_get_clean();
		
		
		//imagedestroy($image);
		imagedestroy($imageSmall);
		imagedestroy($imageSmallGray);
		imagedestroy($imageDCT);
		imagedestroy($imageFinal);
		
		return $wynik;
	}
?>