<?php

namespace App\Repositories;

use App\Models\UserEntity;
use Framework\Database\Connection;

class UserRepository extends UserEntity{
	private $db = null;

	function __construct(){
		$this->db = Connection::getPDO();
	}

	public function isEmailOrPseudoExist(){
		$email = $this->getEmail();
		$pseudo = $this->getPseudo();
		$req = $this->db->prepare("SELECT * FROM user WHERE email=? OR pseudo=?");
		$req->execute(array($email, $pseudo));
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
			date_creation
		) VALUES (
			:pseudo, 
			:password,
			:email,
			TRUE,
			NOW()
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

		$this->setId($user['id']);
		$this->setPseudo($user['pseudo']);
		$this->setPassword($user['password']);
		$this->setEmail($user['email']);
		$this->setDate_creation($user['date_creation']);
		$this->setBio($user['bio']);
		$this->setPhoto_profil($user['password']);
		$this->setActif($user['actif']);
		$this->setDate_last_modification($user['date_last_modification']);

		return $this;
	}

	public function load(){
		$id = $this->getId();
		$req = $this->db->prepare("SELECT * FROM user WHERE id=?");
		$req->execute(array($id));
		$user = $req->fetch();

		$this->setId($user['id']);
		$this->setPseudo($user['pseudo']);
		$this->setPassword($user['password']);
		$this->setEmail($user['email']);
		$this->setDate_creation($user['date_creation']);
		$this->setPhoto_profil($user['photo_profil']);
		$this->setBio($user['bio']);
		$this->setActif($user['actif']);
		$this->setDate_last_modification($user['date_last_modification']);

		return $this;
	}

	public function findSearch($search){
		$s = strtolower($search);
		$req = $this->db->prepare("SELECT * FROM user
			WHERE LOWER(pseudo) LIKE :s
			OR LOWER(email) LIKE :s
			ORDER BY LOWER(pseudo) LIKE :so DESC,LOWER(pseudo) LIKE :s DESC
		");
		$req->execute(array(
			"s" => "%" . $s . "%",
			"so" => $s . "%",
		));
		$users = array();
		while ($user = $req->fetch()){
			$userO = new UserRepository();
			$userO->setId($user['id']);
			$userO->setPseudo($user['pseudo']);
			$userO->setPassword($user['password']);
			$userO->setEmail($user['email']);
			$userO->setDate_creation($user['date_creation']);
			$userO->setPhoto_profil($user['photo_profil']);
			$userO->setBio($user['bio']);
			$userO->setActif($user['actif']);
			$userO->setDate_last_modification($user['date_last_modification']);
			$users[] = $userO;
		}
		return $users;
	}

	public function findSearchInContact($search, $user_id){
		$s = strtolower($search);
		$req = $this->db->prepare("
			SELECT * FROM user u
			INNER JOIN contact c
			ON u.id=c.friend_id
			WHERE 
			c.user_id=:user_id
			AND (LOWER(pseudo) LIKE :s OR LOWER(email) LIKE :s)
			ORDER BY LOWER(pseudo) LIKE :so DESC,LOWER(pseudo) LIKE :s DESC
		");
		$req->execute(array(
			"user_id" => $user_id,
			"s" => "%" . $s . "%",
			"so" => $s . "%",
		));
		$users = array();
		while ($user = $req->fetch()){
			$userO = new UserRepository();
			$userO->setId($user['friend_id']);
			$userO->setPseudo($user['pseudo']);
			$userO->setPassword($user['password']);
			$userO->setEmail($user['email']);
			$userO->setDate_creation($user['date_creation']);
			$userO->setPhoto_profil($user['photo_profil']);
			$userO->setBio($user['bio']);
			$userO->setActif($user['actif']);
			$userO->setDate_last_modification($user['date_last_modification']);
			$users[] = $userO;
		}
		return $users;
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

}
