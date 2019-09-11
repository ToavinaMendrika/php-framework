<?php

namespace App\Database;

use \PDO;

class Connexion {
	private $host = "localhost";

	private $db_name = "simple_chat";

	private $login = "root";

	private $password = "";
	
	public $pdo = null;

	function __construct(){
		$info = "mysql:host=" . $this->host . ";dbname=" . $this->db_name;
		$login = $this->login;
		$password = $this->password;

		$bdd = new PDO($info, $login, $password);
		$this->pdo = $bdd;
	}
}