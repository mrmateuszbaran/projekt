<?php

class Uzytkownik
{
	public $nrodznaki = null;
	public $poziom = null;
	public $komenda = null;
	
	function __construct($nrodznaki, $poziom, $komenda)
	{
		$this->nrodznaki = $nrodznaki;
		$this->poziom = $poziom;
		$this->komenda = $komenda;
	}
}

?>