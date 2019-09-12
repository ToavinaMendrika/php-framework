<?php

namespace App\Repositories;

use App\Models\DiscussionEntity;
use \PDO;

class DiscussionRepository extends DiscussionEntity{
	private $db = null;

	function __construct(){
		$this->setDb();
	}

	public function getDiscussionsFromUserId($user_id){
		$req = $this->db->prepare("
			SELECT * FROM discussion d
			INNER JOIN discussion_user du
			ON d.id=du.discussion_id 
			WHERE du.user_id=?
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
			$discussion->setDate_last_message($discussionArray['date_last_message']); 
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
			$usersArray[] = $user;
		}
		return $usersArray;
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