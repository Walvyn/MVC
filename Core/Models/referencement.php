<?php

/**
* Class représentant la table referencement
* @author Malvyn
*/
class Referencement_Model extends Model {

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
	public $meta = false;

	/**
	* @var text
	* @null YES
	*/
	public $content = false;

	/**
	* @var int(11)
	* @null NO
	* @key MUL
	*/
	public $pid = false;

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
			$this->meta = (isset($data['meta']))? $data['meta'] : false;
			$this->content = (isset($data['content']))? $data['content'] : false;
			$this->pid = (isset($data['pid']))? $data['pid'] : false;
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