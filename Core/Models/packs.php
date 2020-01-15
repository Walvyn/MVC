<?php

/**
* Class représentant la table packs
* @author Malvyn
*/
class Packs_Model extends Model {

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
	* @var varchar(255)
	* @null NO
	* @default Pack
	*/
	public $nom = false;

	/**
	* @var varchar(255)
	* @null NO
	* @default global
	*/
	public $type = false;

	/**
	* @var int(11)
	* @null NO
	*/
	public $prix = false;

	/**
	* @var text
	* @null YES
	*/
	public $description = false;

	/**
	* @var int(11)
	* @null YES
	*/
	public $id_image = false;

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
			$this->type = (isset($data['type']))? $data['type'] : false;
			$this->prix = (isset($data['prix']))? $data['prix'] : false;
			$this->description = (isset($data['description']))? $data['description'] : false;
			$this->id_image = (isset($data['id_image']))? $data['id_image'] : false;
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