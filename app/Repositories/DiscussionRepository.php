<?php

namespace App\Repositories;

use App\Models\DiscussionEntity;
use Framework\Database\Connection;

class DiscussionRepository extends DiscussionEntity{
	private $db = null;

	function __construct(){
		$this->db = Connection::getPDO();
	}

	public function getDiscussionsFromUserId($user_id){
		$req = $this->db->prepare("
			SELECT 
			du.discussion_id,
			date_creation,
			d.type,
			name,
			photo_profil,
			last_message
			FROM discussion_user du
			INNER JOIN discussion d
			ON d.id=du.discussion_id 
			INNER JOIN message m
			ON d.last_message=m.id
			WHERE du.user_id=?
			ORDER BY m.date_envoi DESC
		");
		$req->execute(array($user_id));
		$discussionsArray = array();
		while ($discussionArray = $req->fetch()){
			$discussion = new DiscussionRepository();
			$discussion->setId($discussionArray['discussion_id']); 
			$discussion->setDate_creation($discussionArray['date_creation']); 
			$discussion->setType($discussionArray['type']); 
			$discussion->setName($discussionArray['name']); 
			$discussion->setPhoto_profil($discussionArray['photo_profil']); 
			$discussion->setLast_message($discussionArray['last_message']); 
			$discussionsArray[] = $discussion;
		}
		return $discussionsArray;
	}

	public function getUsersNecessityArray(){
		$id = $this->getId();
		$req = $this->db->prepare("
			SELECT * FROM user u
			INNER JOIN discussion_user du
			ON u.id=du.user_id 
			WHERE du.discussion_id=?
		");
		$req->execute(array($id));

		$usersArray = array();
		while ($userArray = $req->fetch()){
			$user = array();
			$user["id"] = $userArray["user_id"];
			$user["pseudo"] = $userArray["pseudo"];
			$user["photo_profil"] = $userArray["photo_profil"];
			$user["actif"] = $userArray["actif"];
			$usersArray[] = $user;
		}
		return $usersArray;
	}

	public function getNotSeenMessages($user_id){
		$id = $this->getId();
		$req = $this->db->prepare("
			SELECT COUNT(mv.id) as nb FROM message_vu mv
			INNER JOIN message m
			ON mv.message_id=m.id
			INNER JOIN discussion d
			ON m.discussion_id=d.id 
			WHERE d.id=:id
			AND mv.user_id=:user_id
			AND mv.is_seen=FALSE
		");
		$req->execute(array(
			"id" => $id,
			"user_id" => $user_id,
		));

		$msg = $req->fetch();
		$nb = $msg["nb"];
		return $nb;
	}

	public function getMessages(){
		$id = $this->getId();
		$req = $this->db->prepare("
			SELECT * FROM message WHERE discussion_id=? ORDER BY date_envoi ASC
		");
		$req->execute(array(
			$id
		));

		$messagesArray = array();
		while ($messageArray = $req->fetch()){
			$message = new MessageRepository();
			$message->setId($messageArray["id"]);
			$message->setMsg_text($messageArray["msg_text"]);
			$message->setDate_envoi($messageArray["date_envoi"]);
			$message->setType($messageArray["type"]);
			$message->setUser_id($messageArray["user_id"]);
			$message->setDiscussion_id($messageArray["discussion_id"]);
			$messagesArray[] = $message;
		}
		return $messagesArray;
	}

	public function getLast_messageArray(){
		$id = $this->getId();
		$req = $this->db->prepare("
			SELECT m.* FROM message m
			INNER JOIN discussion d
			ON m.id=d.last_message
			WHERE d.id=?
		");
		$req->execute(array(
			$id
		));

		$message = $req->fetch();

		$messageArray = array();
		$messageArray["id"] = $message["id"];
		$messageArray["msg_text"] = utf8_encode($message["msg_text"]);
		$messageArray["date_envoi"] = $message["date_envoi"];
		$messageArray["type"] = $message["type"];
		$messageArray["user_id"] = $message["user_id"];
		$messageArray["discussion_id"] = $message["discussion_id"];

		return $messageArray;
	}

	public function load(){
		$id = $this->getId();
		$req = $this->db->prepare("SELECT * FROM discussion WHERE id=?");
		$req->execute(array($id));
		$discussion = $req->fetch();

		$this->setId($discussion['id']);
		$this->setDate_creation($discussion['date_creation']);
		$this->setType($discussion['type']);
		$this->setName($discussion['name']);
		$this->setPhoto_profil($discussion['photo_profil']);
		$this->setLast_message($discussion['last_message']);

		return $this;
	}

	public function update(){
		$req = $this->db->prepare("UPDATE discussion SET 
			date_creation=:date_creation,
			type=:type,
			name=:name,
			photo_profil=:photo_profil,
			last_message=:last_message
			WHERE id = :id
		");
		$req -> execute(array(
			"id" => $this -> getId(),
			"date_creation" => $this -> getDate_creation(),
			"type" => $this -> getType(),
			"name" => $this -> getName(),
			"photo_profil" => $this -> getPhoto_profil(),
			"last_message" => $this -> getLast_message(),
		));
	}

	public function getDiscuIdFromProfil($user_from, $user_to){
		$req = $this->db->prepare("
			SELECT COUNT(d.id) AS nb, d.id AS id FROM discussion d
			INNER JOIN discussion_user du
			ON d.id=du.discussion_id
			WHERE (du.user_id=?
			OR du.user_id=?)
			AND d.type='individual'
            GROUP BY d.id
		");
		$req -> execute(array(
			$user_from,
			$user_to,
		));
		$id = NULL;
		while ($d = $req->fetch()) {
			if ($d['nb'] == 2){
				$id = $d['id'];
				break;
			}
		}
		if ($id != NULL){
			return $id;
		}
		else{
			$id = $this->create([$user_from, $user_to]);
			return $id;
		}
	}

	public function create($arrayUsers, $type="individual"){
		if (!in_array($type, ["individual", "group"])){
			$type = "individual";
		}
		if (empty($arrayUsers)){
			return NULL;
		}
		$req = $this->db->prepare("
			INSERT INTO discussion (
				date_creation,
				type,
				name,
				photo_profil,
				last_message
			)
			VALUES (
				NOW(),
				:type,
				NULL,
				NULL,
				NULL
			)
		");
		$req -> execute(array(
			'type' => $type,
		));
		$last_id = $this->db->lastInsertId();

		foreach ($arrayUsers as $user_id) {
			$req = $this->db->prepare("
				INSERT INTO discussion_user (
					discussion_id,
					user_id
				)
				VALUES (
					?,
					?
				)
			");
			$req -> execute(array(
				$last_id,
				$user_id
			));
		}

		return $last_id;
	}
}