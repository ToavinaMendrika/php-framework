<?php

namespace App\Repositories;

use App\Models\MessageEntity;
use Framework\Database\Connection;

class MessageRepository extends MessageEntity{
	private $db = null;

	function __construct(){
		$this->db = Connection::getPDO();
	}

	public function create(){
		$msg_text = $this->getMsg_text();
		$type = $this->getType();
		$user_id = $this->getUser_id();
		$discu_id = $this->getDiscussion_id();
		$req = $this->db->prepare("INSERT INTO message (
			msg_text,
			date_envoi,
			type,
			user_id,
			discussion_id
		) VALUES (
			:msg_text,
			NOW(),
			:type,
			:user_id,
			:discu_id
		)");
		$req -> execute(array(
			"msg_text" => $msg_text,
			"type" => $type,
			"user_id" => $user_id,
			"discu_id" => $discu_id,
		));

		$req = $this->db->prepare("SELECT * FROM message WHERE 
			user_id=:user_id 
			AND discussion_id=:discu_id
			ORDER BY date_envoi DESC
		");
		$req -> execute(array(
			"user_id" => $user_id,
			"discu_id" => $discu_id,
		));
		$message = $req->fetch();
		$this->setId($message['id']);

		// Modification (insertion) de la table message_vu
		$this->initiateMessage_vu();

		return $this;
	}

	public function getDiscussion(){
		$discu_id = $this->getDiscussion_id();
		$discussion = new DiscussionRepository();
		$discussion->setId($discu_id);
		$discussion->load();
		return $discussion;
	}

	private function createMessage_vu($is_seen=false, $user_id){
		$message_id = $this->getId();

		if ($is_seen){
			$req = $this->db->prepare("INSERT INTO message_vu (
			is_seen,
			date_seen,
			user_id,
			message_id
			) VALUES (
				TRUE,
				NOW(),
				:user_id,
				:message_id
			)");
		}
		else{
			$req = $this->db->prepare("INSERT INTO message_vu (
			is_seen,
			date_seen,
			user_id,
			message_id
			) VALUES (
				FALSE,
				NULL,
				:user_id,
				:message_id
			)");
		}
		
		$req -> execute(array(
			"user_id" => $user_id,
			"message_id" => $message_id,
		));
	}

	private function initiateMessage_vu(){
		$user_id = $this->getUser_id();

		$discussion = $this->getDiscussion();
		$users = $discussion->getUsersNecessityArray();

		foreach ($users as $user) {
			if ($user['id'] == $user_id){
				$this->createMessage_vu(true, $user['id']);
			}
			else {
				$this->createMessage_vu(false, $user['id']);
			}
		}
	}

	public function getUserNecessityArray(){
		$id = $this->getId();
		$req = $this->db->prepare("
			SELECT * FROM user u
			INNER JOIN message m
			ON u.id=m.user_id 
			WHERE m.id=?
		");
		$req->execute(array($id));

		$userArray = $req->fetch();

		$user = array();
		$user["id"] = $userArray["user_id"];
		$user["pseudo"] = $userArray["pseudo"];
		$user["photo_profil"] = $userArray["photo_profil"];

		return $user;
	}

	public function isCurrentUser($user_id){
		$id = $this->getId();
		$req = $this->db->prepare("
			SELECT * FROM user u
			INNER JOIN message m
			ON u.id=m.user_id 
			WHERE m.id=?
		");
		$req->execute(array($id));

		$userArray = $req->fetch();

		$isCurrentUser = $userArray['user_id'] == $user_id ? true : false;

		return $isCurrentUser;
	}
}