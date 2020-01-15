<?php

/**
* Class représentant la table users
* @author Malvyn
*/
class Users_Model extends Model {

	/**
	* Nom de la configuration utilisé par le modèle
	* @var String
	*/
	const CONFIG = 'default';

	/**
	* @var int(11)
	* @null NO
	* @key PRI
	* @options auto_increment
	*/
	private $id = false;

	/**
	* @var varchar(45)
	* @null NO
	*/
	public $nom = false;

	/**
	* @var varchar(255)
	* @null NO
	*/
	public $mot_de_passe = false;

	/**
	* @var varchar(255)
	* @null NO
	*/
	public $email = false;

	/**
	* @var varchar(10)
	* @null YES
	*/
	public $telephone = false;

	/**
	* Nom de la clé primaire
	* @var String
	*/
	private $_primaryKey_ = 'id';


	/**
	* Hydrate les données
	* @param $data Données
	*/
	public function __construct($data = null){
		if($data != null){
			$this->id = (isset($data['id']))? $data['id'] : false;
			$this->nom = (isset($data['nom']))? $data['nom'] : false;
			$this->mot_de_passe = (isset($data['mot_de_passe']))? $data['mot_de_passe'] : false;
			$this->email = (isset($data['email']))? $data['email'] : false;
			$this->telephone = (isset($data['telephone']))? $data['telephone'] : false;
		}
	}

	/**
	* Getter de l'attribut $id
	* @return int(11)
	*/
	public function id(){
		return $this->id;
	}

	/**
	* Getter de l'attribut $_primaryKey_
	* @return String
	*/
	public function _primaryKey_(){
		return $this->_primaryKey_;
	}
}

?>