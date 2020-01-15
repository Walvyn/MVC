<?php

/**
* Class représentant la table pages
* @author Malvyn
*/
class Pages_Model extends Model {

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
	* @var varchar(100)
	* @null NO
	*/
	public $nom = false;

	/**
	* @var varchar(255)
	* @null YES
	*/
	public $titre = false;

	/**
	* @var text
	* @null YES
	*/
	public $content = false;

	/**
	* @var int(11)
	* @null NO
	*/
	public $ordre = false;

	/**
	* @var varchar(45)
	* @null NO
	*/
	public $type = false;

	/**
	* @var int(11)
	* @null NO
	*/
	public $online = false;

	/**
	* @var varchar(255)
	* @null NO
	*/
	public $url = false;

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
			$this->titre = (isset($data['titre']))? $data['titre'] : false;
			$this->content = (isset($data['content']))? $data['content'] : false;
			$this->ordre = (isset($data['ordre']))? $data['ordre'] : false;
			$this->type = (isset($data['type']))? $data['type'] : false;
			$this->online = (isset($data['online']))? $data['online'] : false;
			$this->url = (isset($data['url']))? $data['url'] : false;
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