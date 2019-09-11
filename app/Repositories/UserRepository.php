<?php

namespace App\Repositories;

use App\Models\UserEntity;
use \PDO;

class UserRepository extends UserEntity{
	private $db = null;

	function __construct(){
		$this->setDb();
	}

	public function isEmailExists(){
		$email = $this->getEmail();
		$req = $this->db->prepare("SELECT * FROM user WHERE email=?");
		$req->execute(array($email));
		$user = $req->fetch();
		$isExist = $user == false ? false : true;
		return $isExist;
	}

	public function isUserExists(){
		$email = $this->getEmail();
		$password = $this->getPassword();
		$req = $this->db->prepare("SELECT * FROM user WHERE email=? AND password=?");
		$req->execute(array($email, $password));
		$user = $req->fetch();
		$isExist = $user == false ? false : true;
		return $isExist;
	}

	public function create(){
		$email = $this->getEmail();
		$pseudo = $this->getPseudo();
		$password = $this->getPassword();
		$req = $this->db->prepare("INSERT INTO user (
			pseudo, 
			password, 
			email,
			actif,
			date_creation,
			token
		) VALUES (
			:pseudo, 
			:password,
			:email,
			TRUE,
			NOW(),
			''
		)");
		$req -> execute(array(
			"pseudo" => $pseudo,
			"password" => $password,
			"email" => $email,
		));

		$req = $this->db->prepare("SELECT * FROM user WHERE email=?");
		$req->execute(array($email));
		$user = $req->fetch();
		$this->setId($user['id']);

		return $this;
	}

	public function loadByEmail(){
		$email = $this->getEmail();
		$req = $this->db->prepare("SELECT * FROM user WHERE email=?");
		$req->execute(array($email));
		$user = $req->fetch();

		$this->setPseudo($user['pseudo']);
		$this->setPassword($user['password']);
		$this->setDate_creation($user['date_creation']);
		$this->setBio($user['bio']);
		$this->setPhoto_profil($user['password']);
		$this->setActif($user['actif']);
		$this->setDate_last_modification($user['date_last_modification']);

		return $this;
	}

	public function selectUserByPseudo(){

	}

	public function selectUserById(){
		
	}

	public function update(){
		$req = $this->db->prepare("UPDATE user SET 
			pseudo = :pseudo,
			password = :password,
			email = :email,
			photo_profil = :photo_profil,
			bio = :bio,
			actif = :actif,
			date_last_modification = NOW()
			WHERE id = :id
		");
		$req -> execute(array(
			"id" => $this -> getId(),
			"pseudo" => $this -> getPseudo(),
			"password" => $this -> getPassword(),
			"email" => $this -> getEmail(),
			"photo_profil" => $this -> getPhoto_profil(),
			"bio" => $this -> getBio(),
			"actif" => $this -> getActif(),
		));
	}

	private function setDb(){
		$host = "localhost";
		$db_name = "simple_chat";
		$login = "root";
		$password = "";

		$info = "mysql:host=" . $host . ";dbname=" . $db_name;
		$login = $login;
		$password = $password;

		$bdd = new PDO($info, $login, $password);
		$this->db = $bdd;
	}
}