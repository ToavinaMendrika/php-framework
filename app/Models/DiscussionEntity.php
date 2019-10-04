<?php

namespace App\Models;

/**
* Model DiscussionEntity
*
* @author David Rambolajaona <david.rambolajaon@esti.mg>
*/
class DiscussionEntity {
	private $id;

	private $date_creation;

	private $type;

	private $name;

	private $photo_profil;

	private $last_message;

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
		$this->name = utf8_encode($name);
	}

	public function getPhoto_profil(){
		return $this->photo_profil;
	}

	public function setPhoto_profil($photo_profil){
		$this->photo_profil = $photo_profil;
	}

	public function getLast_message(){
		return $this->last_message;
	}

	public function setLast_message($last_message){
		$this->last_message = $last_message;
	}
}