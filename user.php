<?php

class Uzytkownik
{
	public $login = null;
	public $poziom = null;
	public $komenda = null;
	
	function __construct($login, $poziom, $komenda)
	{
		$this->login = $login;
		$this->poziom = $poziom;
		$this->komenda = $komenda;
	}
	
	function ustawLogin($login)
	{
		$this->login = $login;
	}
	function ustawPoziom($poziom)
	{
		$this->poziom = $poziom;
	}
}

?>