<?php

namespace App\Repositories;

use App\Models\UserEntity;
use Framework\Database\Connection;

/**
* Repository DiscussionRepository
*
* @author David Rambolajaona <david.rambolajaon@esti.mg>
*/
class UserRepository extends UserEntity{

	/**
	* PDO
	*
	* @var object
	*/
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

		$lastId = $this->db->lastInsertId();
		$this->setId($lastId);

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

	public function updatePhotoProfil($fileName){
		$req = $this->db->prepare("
			INSERT INTO photo_profil (
				name_photo,
				date_creation,
				type,
				user_discussion_id
			)
			VALUES (
				:name_photo,
				NOW(),
				'user',
				:id
			)
		");
		$req -> execute(array(
			"id" => $this -> getId(),
			"name_photo" => $fileName,
		));

		$lastId = $this->db->lastInsertId();

		return $lastId;
	}

	public function getPhotoInfo($photo_id){
		if ($photo_id == null){
			return array();
		}
		$req = $this->db->prepare("
			SELECT * FROM photo_profil
			WHERE id=?
		");
		$req -> execute(array(
			$photo_id,
		));

		$photo = $req->fetch();
		$photoArray = array();
		foreach ($photo as $key => $value) {
			if ($key != "0" AND (int)$key == 0){
				$photoArray[$key] = $value;
			}
		}
		$link = $_SERVER['APP_URL'] . '/simple-chat/public/images/user/' . $photoArray['name_photo'];
		$photoArray["link_photo"] = $link;

		return $photoArray;
	}

	public function getNbNotSeen(){
		$id = $this->getId();
		$req = $this->db->prepare("
			SELECT COUNT(id) as nb FROM demande 
			WHERE actif = TRUE
			AND is_seen = FALSE
			AND receive_id = ?
		");
		$req->execute(array($id));
		$nb = $req->fetch();
		return (int)$nb['nb'];
	}

	public function isUserIdExists($id){
		$req = $this->db->prepare("SELECT * FROM user 
			WHERE id = ?
		");
		$req->execute(array($id));
		$user = $req->fetch();
		$exists = $user == false ? false : true;
		return $exists;
	}

	public function sendRequest($from_id, $to_id){
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
			if ($this->isUserIdExists($to_id)){
				$this->createDemande($from_id, $to_id);
				$resultat["status"] = "success";
				$resultat["message"] = "Demande en cours";
			}
			else {
				$resultat["status"] = "error";
				$resultat["message"] = "L'utilisateur à envoyer la demande n'existe pas";
			}
		}
		else{
			$is = $demande["is_accepted"];
			if ($is == NULL){
				$this->annulationDemande($from_id, $to_id);
				$resultat["status"] = "success";
				$resultat["message"] = "Annulation de la demande";
			}
			else if ($is == TRUE){
				$this->deleteContact($from_id, $to_id);
				$resultat["status"] = "success";
				$resultat["message"] = "Suppression du contact";
			}
		}

		return $resultat;
	}

	public function deleteContact($from_id, $to_id){
		$req = $this->db->prepare("
			DELETE FROM contact
			WHERE (user_id=:user_id AND friend_id=:friend_id)
			OR (user_id=:friend_id AND friend_id=:user_id)
		");
		$req->execute(array(
			"user_id" => $from_id,
			"friend_id" => $to_id,
		));

		$req = $this->db->prepare("
			UPDATE demande SET
			actif = FALSE,
			date_suppression_contact = NOW()
			WHERE send_id=?
			AND receive_id=?
			AND actif=TRUE
		");
		$req->execute(array($from_id, $to_id));
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
				is_seen,
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
				FALSE,
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
			AND d.is_accepted IS NULL
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
				$userArray["photo_link"] = $userO->getPhotoInfo($userO->getPhoto_profil());
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
			AND actif=TRUE
		");
		$req->execute(array($from_id, $to_id));
	}

	public function seeRequests(){
		$id = $this->getId();
		$req = $this->db->prepare("
			UPDATE demande SET
			is_seen=TRUE
			WHERE receive_id=?
			AND actif=TRUE
		");
		$req->execute(array($id));
		$count = $req->rowCount();
		return $count;
	}

	public function responseOfRequest($send_id, $accept=true){
		$id = $this->getId();
		$req = $this->db->prepare("
			SELECT * FROM demande
			WHERE send_id=:send_id
			AND receive_id=:receive_id
			AND actif=TRUE 
		");
		$req->execute(array(
			"send_id" => $send_id,
			"receive_id" => $id,
		));
		$demande = $req->fetch();
		$toModify = $demande["is_accepted"] == NULL ? true : false;
		if ($accept){
			$req = $this->db->prepare("
				UPDATE demande SET
				is_accepted=TRUE,
				date_acceptation=NOW()
				WHERE send_id=:send_id
				AND receive_id=:receive_id
				AND actif=TRUE 
			");
			$req->execute(array(
				"send_id" => $send_id,
				"receive_id" => $id,
			));
		}
		else{
			$req = $this->db->prepare("
				UPDATE demande SET
				is_accepted=FALSE,
				date_refus=NOW(),
				actif=FALSE
				WHERE send_id=:send_id
				AND receive_id=:receive_id
				AND actif=TRUE 
			");
			$req->execute(array(
				"send_id" => $send_id,
				"receive_id" => $id,
			));
		}

		$count = $req->rowCount();
		$is_ok = ($count > 0 AND $toModify)? true : false;

		if ($accept AND $is_ok){
			$this->addContact($id, $send_id);
		}
		return $is_ok;
	}

	public function addContact($user_id, $friend_id){
		$req = $this->db->prepare("
			INSERT INTO contact (user_id, friend_id)
			VALUES (:user_id, :friend_id), (:friend_id, :user_id)
		");
		$req->execute(array(
			"user_id" => $user_id,
			"friend_id" => $friend_id,
		));
	}

	public function isFriendOf($user_id){
		$id = $this->getId();
		$req = $this->db->prepare("SELECT * FROM demande 
			WHERE ((send_id=:send_id 
			AND receive_id=:receive_id)
			OR (send_id=:receive_id 
			AND receive_id=:send_id))
			AND actif=TRUE
		");
		$req->execute(array(
			'send_id' => $id,
			'receive_id' => $user_id,
		));
		$demande = $req->fetch();

		$is_friend = "false";
		if ($demande == false){
			$is_friend = "false";
		}		
		else{
			if ($demande['is_accepted'] == true){
				$is_friend = "true";
			}
			else if ($demande['is_accepted'] == null){
				$is_friend = $demande['send_id'] == $user_id ? "en cours d'envoi" : "en cours de réception";
			}
		}

		return $is_friend;
	}

	public function isPasswordTrue(){
		$id = $this->getId();
		$password = $this->getPassword();
		$req = $this->db->prepare("SELECT * FROM user 
			WHERE id=?
			AND password=?
		");
		$req->execute(array(
			$id,
			$password,
		));
		$user = $req->fetch();
		if ($user == false){
			return false;
		}
		else {
			return true;
		}
	}

	public function getArrayVersion(){
		$req = $this->db->prepare("SELECT * FROM user WHERE 
			id=? 
		");
		$req -> execute(array(
			$this->getId(),
		));
		$user = $req->fetch();
		$userArray = array();
		foreach ($user as $key => $value) {
			if ($key != "0" AND (int)$key == 0){
				$userArray[$key] = $value;
			}
		}
		return $userArray;
	}

	public function getListContact($user_id){
		$req = $this->db->prepare("
			SELECT u.* FROM user u
			INNER JOIN contact c
			ON u.id=c.friend_id
			WHERE c.user_id=?
			ORDER BY u.pseudo ASC
		");
		$req->execute(array(
			$user_id,
		));

		$userArray = array();
		while ($friend = $req->fetch()){
			$user = new UserRepository();
			$user->setId($friend["id"]);
			$uArr = $user->getArrayVersion();
			unset($uArr["password"]);
			$uArr["photo_info"] = $user->getPhotoInfo($uArr["photo_profil"]);
			$userArray[] = $uArr;
		}
		return $userArray;
	}

	public function getNbFriends($user_id){
		$req = $this->db->prepare("
			SELECT COUNT(id) as nb FROM contact 
			WHERE user_id=?
		");
		$req->execute(array($user_id));
		$nb = $req->fetch();
		return (int)$nb['nb'];
	}

}
