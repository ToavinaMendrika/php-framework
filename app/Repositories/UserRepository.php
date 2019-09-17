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

	public function addRequest($from_id, $to_id){
		$req = $this->db->prepare("SELECT * FROM demande 
			WHERE send_id=? 
			AND receive_id=?
			AND actif=TRUE
		");
		$req->execute(array($from_id, $to_id));
		$demande = $req->fetch();

		$resultat = array(
			"status" => "error",
			"message" => "",
		);
		if ($demande == false){
			$this->createDemande($from_id, $to_id);
			$resultat["status"] = "success";
			$resultat["message"] = "Demande en cours";
		}
		else{
			$is = $demande["is_accepted"];
			if ($is == NULL){
				$this->annulationDemande($from_id, $to_id);
				$resultat["status"] = "success";
				$resultat["message"] = "Annulation de la demande";
			}
		}

		return $resultat;
	}

	public function createDemande($from_id, $to_id){
		$req = $this->db->prepare("
			INSERT INTO demande(
				send_id, 
				receive_id, 
				is_accepted, 
				date_envoi, 
				date_acceptation,
				date_refus,
				date_annulation,
				actif
			) 
			VALUES (
				?, 
				?, 
				NULL, 
				NOW(), 
				NULL,
				NULL,
				NULL,
				TRUE
			)
		");
		$req->execute(array($from_id, $to_id));
	}

	public function getAllUsersFromRequest($array=false){
		$id = $this->getId();
		$req = $this->db->prepare("SELECT * FROM user u
			INNER JOIN demande d
			ON u.id=d.send_id
			WHERE receive_id=?
			AND d.actif=TRUE
			ORDER BY d.date_envoi DESC
		");
		$req->execute(array($id));

		$usersArray = array();
		if (!$array){
			while ($user = $req->fetch()){
				$userO = new UserRepository();
				$userO->setId($user["send_id"]);
				$userO->load();
				$usersArray[] = $userO;
			}
		}
		else{
			while ($user = $req->fetch()){
				$userArray = array();
				$userO = new UserRepository();
				$userO->setId($user["send_id"]);
				$userO->load();
				$userInfo = array();
				$userArray["date_envoi"] = $user["date_envoi"];
				$userArray["id"] = $userO->getId();
				$userArray["pseudo"] = $userO->getPseudo();
				$userArray["photo_profil"] = $userO->getPhoto_profil();
				$userArray["bio"] = $userO->getBio();
				$userArray["actif"] = $userO->getActif();
				$usersArray[] = $userArray;
			}
		}

		return $usersArray;
	}

	public function annulationDemande($from_id, $to_id){
		$req = $this->db->prepare("
			UPDATE demande SET
			date_annulation=NOW(),
			actif=FALSE
			WHERE send_id=?
			AND receive_id=?
		");
		$req->execute(array($from_id, $to_id));
	}

}