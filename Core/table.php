<?php 

	/**
	 * Classe gérant les requêtes sur les bases de données (create, drop, truncate)
	 * @author Malvyn
	 */
	class Table extends Engine {

		/**
		 * Différents type de requêtes possibles
		 * @var Int
		 */
		const CREATE	= 0;
		const DROP 		= 1;
		const TRUNCATE 	= 2;

		/**
		 * Liste des colonnes de la table à ajouter
		 * @var Array
		 */
		private $columns = false;

		/**
		 * Liste des options des colonnes de la table à ajouter
		 * @var Array
		 */
		private $options = false;

		/**
		 * Retourne un nouvel objet de cette classe
		 * @param $config Nom de la config à utiliser
		 * @return Objet
		 */
		public static function query($config = 'default'){
			return new Table($config);
		}

		/**
		 * Définit la table sur lequel on fait la requête
		 * @param  String $table Nom de la table
		 * @return Objet | Bool
		 */
		public function from($table){
			if(is_string($table) && !empty($table)){
				$this->table = $table;
				return $this;
			} else {
				debug('Nom de la table incorrect !');
				return false;
			}
		}

		/**
		 * Ajoute un colonne à la liste des colonnes de la table
		 * @param  String $name Nom de la colonne
		 * @param  Array | String $options Options de la colonne (NOT NULL ...)
		 * @return Objet | Bool
		 */
		public function addColumn($name, $options='VARCHAR(255)'){
			if(is_string($name) && !empty($name)){
				if(is_string($options) && !empty($options)){
					$this->options[] = $options;
				} else if(is_array($options)) {
					$this->options[] = implode(' ', $options);
				} else {
					debug('Options de la colonne incorrecte !');
					return false;
				}

				$this->columns[] = $name;
				return $this;
			} else {
				debug('Nom de la colonne incorrecte !');
				return false;
			}
		}

		/**
		 * Construit la requête en fonction du type de requête
		 * @return String | Bool
		 */
		public function getSQL($type = false){
			if($type !== false && is_int($type)){
				$this->type = $type;
			}

			if($this->table){
				switch($this->type){
					case self::CREATE:
						if($this->columns != false){
							$sql = 'CREATE TABLE IF NOT EXISTS '.$this->prefix.$this->table.' (';
							foreach($this->columns as $key => $column){
								$sql .= $column.' '.$this->options[$key].', ';
							}
							$sql = substr($sql, 0, -2);
							$sql .=')';
						} else {
							return false;
						}
						break;

					case self::DROP:
						$sql = 'DROP TABLE IF EXISTS '.$this->prefix.$this->table;
						break;

					case self::TRUNCATE:
						$sql = 'TRUNCATE TABLE '.$this->prefix.$this->table;
						break;

					default :
						debug('Type de requête inconnu !');
						return false;
				}
			} else {
				debug('Table non indiquée, appeler la méthode from() !');
				return false;
			}

			return $sql;
		}


		public function create($refresh = false){
			$this->type = self::CREATE;
			$sql = $this->getSQL();
			

			if($sql){
				$this->prepare($sql);
				if($this->prepare->execute()){
					if($refresh){
						Model::refresh($this->config, true);
					}

					return true;
				}

				return false;
			} else {
				return false;
			}	
		}

		/**
		 * Effectue un DROP sur la table
		 * @return Objet | Bool
		 */
		public function drop(){
			$this->type = self::DROP;
			$sql = $this->getSQL();

			if($sql){				
				$this->prepare($sql);
				if($this->prepare->execute()){
					unlink(CORE.DS.'Models'.DS.(strtolower($this->table)).'.php');
					return true;
				}

				return false;
			} else {
				return false;
			}
		}

		/**
		 * Effectue un TRUNCATE sur la table
		 * @return Objet | Bool
		 */
		public function truncate(){
			$this->type = self::TRUNCATE;
			$sql = $this->getSQL();

			if($sql){
				$this->prepare($sql);
				$this->prepare->execute();
				
				return true;
			} else {
				return false;
			}
		}
	}