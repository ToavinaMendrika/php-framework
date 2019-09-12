<?php

namespace App\Models;

class DiscussionEntity {
	private $id;

	private $date_creation;

	private $type;

	private $name;

	private $photo_profil;

	private $date_last_messgae;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getDate_creation(){
		return $this->date_creation;
	}

	public function setDate_creation($date_creation){
		$this->date_creation = $date_creation;
	}

	public function getType(){
		return $this->type;
	}

	public function setType($type){
		$this->type = $type;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function getPhoto_profil(){
		return $this->photo_profil;
	}

	public function setPhoto_profil($photo_profil){
		$this->photo_profil = $photo_profil;
	}

	public function getDate_last_message(){
		return $this->date_last_messgae;
	}

	public function setDate_last_message($date_last_message){
		$this->date_last_message = $date_last_message;
	}
}