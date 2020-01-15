<?php

/**
* Class représentant la table chapiteaux
* @author Malvyn
*/
class Chapiteaux_Model extends Model {

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
	* @null YES
	*/
	public $nom = false;

	/**
	* @var varchar(45)
	* @null NO
	*/
	public $taille = false;

	/**
	* @var varchar(255)
	* @null NO
	*/
	public $capacite = false;

	/**
	* @var int(11)
	* @null NO
	*/
	public $tarif = false;

	/**
	* @var int(11)
	* @null NO
	*/
	public $prix_demontage = false;

	/**
	* @var text
	* @null NO
	*/
	public $options = false;

	/**
	* @var int(11)
	* @null NO
	*/
	public $rayon = false;

	/**
	* @var float
	* @null NO
	*/
	public $prix_km = false;

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
			$this->taille = (isset($data['taille']))? $data['taille'] : false;
			$this->capacite = (isset($data['capacite']))? $data['capacite'] : false;
			$this->tarif = (isset($data['tarif']))? $data['tarif'] : false;
			$this->prix_demontage = (isset($data['prix_demontage']))? $data['prix_demontage'] : false;
			$this->options = (isset($data['options']))? $data['options'] : false;
			$this->rayon = (isset($data['rayon']))? $data['rayon'] : false;
			$this->prix_km = (isset($data['prix_km']))? $data['prix_km'] : false;
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