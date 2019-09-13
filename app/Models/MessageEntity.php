<?php

namespace App\Models;

class MessageEntity {
	private $id;

	private $msg_text;

	private $date_envoi;

	private $type;

	private $user_id;

	private $discussion_id;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getMsg_text(){
		return $this->msg_text;
	}

	public function setMsg_Text($msg_text){
		$this->msg_text = utf8_encode($msg_text);
	}

	public function getDate_envoi(){
		return $this->date_envoi;
	}

	public function setDate_envoi($date_envoi){
		$this->date_envoi = $date_envoi;
	}

	public function getType(){
		return $this->type;
	}

	public function setType($type){
		$this->type = $type;
	}

	public function getUser_id(){
		return $this->user_id;
	}

	public function setUser_id($user_id){
		$this->user_id = $user_id;
	}

	public function getDiscussion_id(){
		return $this->discussion_id;
	}

	public function setDiscussion_id($discussion_id){
		$this->discussion_id = $discussion_id;
	}
}