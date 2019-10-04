<?php

namespace App\Models;

/**
* Model UserEntity
*
* @author David Rambolajaona <david.rambolajaon@esti.mg>
*/
class UserEntity {
	private $id;

	private $pseudo;

	private $password;

	private $email;

	private $date_creation;

	private $photo_profil;

	private $bio;

	private $actif;

	private $date_last_modification;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getPseudo(){
		return $this->pseudo;
	}

	public function setPseudo($pseudo){
		$this->pseudo = utf8_encode($pseudo);
	}

	public function getPassword(){
		return $this->password;
	}

	public function setPassword($password){
		if (strlen($password) == 32){
			$this->password = $password;
		}
		else {
			$this->password = md5($password);
		}
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getDate_creation(){
		return $this->date_creation;
	}

	public function setDate_creation($date_creation){
		$this->date_creation = $date_creation;
	}

	public function getPhoto_profil(){
		return $this->photo_profil;
	}

	public function setPhoto_profil($photo_profil){
		$this->photo_profil = $photo_profil;
	}

	public function getBio(){
		return $this->bio;
	}

	public function setBio($bio){
		$this->bio = $bio;
	}

	public function getActif(){
		return $this->actif;
	}

	public function setActif($actif){
		$this->actif = $actif;
	}

	public function getDate_last_modification(){
		return $this->date_last_modification;
	}

	public function setDate_last_modification($date_last_modification){
		$this->date_last_modification = $date_last_modification;
	}
}