<?php 

	/**
	 * Classe les requêtes sur les tables des base de données (select, insert, update, delete)
	 * @author Malvyn
	 */
	class Db extends Engine {

		/**
		 * Différents type de requêtes possibles
		 * @var Int
		 */
		const SELECT	= 0;
		const DELETE	= 1;
		const COUNT 	= 2;
		const UPDATE 	= 3;
		const INSERT 	= 4;

		/**
		 * Contient les champs de la requête
		 * @var Bool | Array
		 */
		private $fields = false;

		/**
		 * Liste des colonnes à mettre à jour
		 * @var Array
		 */
		private $set = array();

		/**
		 * Condition de la requête
		 * @var Bool | String
		 */
		private $condition = false;

		/**
		 * Paramètres
		 * @var Array
		 */
		private $params = array();

		/**
		 * Clause ORDER BY dans à la requête
		 * @var Bool | String
		 */
		private $order = false;

		/**
		 * Clause LIMIT dans à la requête
		 * @var Bool | String
		 */
		private $limit = false;

		/**
		 * Retourne un nouvel objet de cette classe
		 * @param $config Nom de la config à utiliser
		 * @return Objet
		 */
		public static function query($config = 'default'){
			return new Db($config);
		}

		/**
		 * Retourne un nouvel objet du modèle de la table
		 * @param $table Nom de la table
		 * @return Objet
		 */
		public static function getTable($table, $data = null){
			if(file_exists(CORE.DS.'Models'.DS.$table.'.php')){
				$name = ucfirst($table).'_Model';
				return new $name($data);
			} else {
				debug('Nom de la table incorrect !');
				return false;
			}
		}

		/**
		 * Définit les champs à sélectionner dans la requête
		 * @param  String | Array $fields Liste des champs
		 * @return Objet | Bool
		 */
		public function fields($fields){
			if(empty($fields)){
				debug('Liste des champs vide !');
				return false;
			}

			if(is_string($fields)){
				$fields = explode(',', $fields);
			}

			if(is_array($fields)){
				if(!$this->fields){
					$this->fields = $fields;
				} else {
					$this->fields = array_merge($this->fields, $fields);
				}

				return $this;
			} else {
				debug('Champ(s) incorrect !');
				return false;
			}
		}

		/**
		 * Définit les colonnes à mettre à jour ainsi que leurs nouvelles valeurs
		 * @param  String $column les colonnes
		 * @param  MultiType $params paramètres
		 * @return Objet | Bool
		 */
		public function set($column, $params = array()){
			if(is_string($column) && !empty($column)){

				if(preg_match('#^[\w0-9_\.-]+ = .+$#', $column)){
					$column = trim($column, ' ');
					$column = trim($column, ',');
					$this->set = array_merge($this->set, explode(',', $column));

					if(is_array($params)){
						$this->params = array_merge($this->params, $params);
					} else if(is_string($params) || is_int($params)) {
						$this->params[] = $params;
					}

				} else if(preg_match('#^[\w0-9_\.-]+$#', $column)){
					$this->set[] = $column.' = ?';

					if(is_string($params) || is_int($params) || is_null($params)) {
						$this->params[] = $params;
					} else {
						debug('Paramètre incorrect !');
						return false;
					}

				} else {
					debug('Liste des colonnes incorrect !');
					return false;
				}

				return $this;			
			} else if(is_a($column, 'Model')) {
				$set = array();

				foreach(get_object_vars($column) as $name => $value){
					if($value !== false){
						$set[] = $name.' = ?';
						$params[] = $value;
					}
				}

				$this->set = array_merge($this->set, $set);
				$this->params = array_merge($this->params, $params);

				return $this;
			} else {
				debug('Liste des colonnes incorrect !');
				return false;
			}
		}

		/**
		 * Définit la table sur lequel on fait la requête
		 * @param  String $table Nom de la table
		 * @return Objet | Bool
		 */
		public function from($table){
			if(is_string($table) && !empty($table) && file_exists(CORE.DS.'Models'.DS.strtolower($table).'.php')){
				$this->table = strtolower($table);
				return $this;
			} else {
				debug('Nom de la table incorrect !');
				return false;
			}
		}

		/**
		 * Ajoute une clause WHERE à la requête (à utiliser pour la première clause where)
		 * @param  String $condition condition
		 * @param  MultiType $params paramètres
		 * @return Objet | Bool
		 */
		public function where($condition, $params = array()){
			if(is_string($condition) && !empty($condition)){
				$this->condition = $condition;
				
				if(is_array($params)){
					$this->params = array_merge($this->params, $params);
				} else if(is_string($params) || is_int($params)) {
					$this->params[] = $params;
				}

				return $this;
			} else {
				debug('Condition incorrect !');
				return false;
			}
		}

		/**
		 * Ajoute un AND dans la clause WHERE à la requête
		 * @param  String $condition condition
		 * @param  MultiType $params paramètres
		 * @return Objet | Bool
		 */
		public function andWhere($condition, $params = array()){
			if(is_string($condition) && !empty($condition)){
				if(!$this->condition){
					debug('Il faut tout d\'abord appeler la méthode where() !');
					return false;
				}

				$this->condition .= ' AND '.$condition;

				if(is_array($params)){
					$this->params = array_merge($this->params, $params);
				} else if(is_string($params) || is_int($params)) {
					$this->params[] = $params;
				}

				return $this;
			} else {
				debug('Condition incorrect !');
				return false;
			}
		}

		/**
		 * Ajoute un OR dans la clause WHERE à la requête
		 * @param  String $condition condition
		 * @param  MultiType $params paramètres
		 * @return Objet | Bool
		 */
		public function orWhere($condition, $params = array()){
			if(is_string($condition) && !empty($condition)){
				if(!$this->condition){
					debug('Il faut tout d\'abord appeler la méthode where() !');
					return false;
				}

				$this->condition .= ' OR '.$condition;

				if(is_array($params)){
					$this->params = array_merge($this->params, $params);
				} else if(is_string($params) || is_int($params)) {
					$this->params[] = $params;
				}

				return $this;
			} else {
				debug('Condition incorrect !');
				return false;
			}
		}

		/**
		 * Ajoute dans la clause WHERE une comparaison avec une sous-requête
		 * FAIRE ATTENTION A NE PAS UTILISER LES MEME MOTS CLES DANS LES CLAUSE WHERE QUE LA REQUETE PRINCIPAL (:mot)
		 * @param  String $condition condition
		 * @param  Objet $db Objet Db contenant la sous-requête
		 * @return Objet | Bool
		 */
		public function whereSelect($condition, Db $db){
			if(is_string($condition) && !empty($condition)){
				$sql = $db->getSQL();

				if($sql){
					if(!$this->condition){
						$this->condition = '';
					}

					$this->condition .= $condition.' ('.$sql.')';
					$this->params = array_merge($this->params, $db->getParams());

					return $this;
				} else {
					debug('Sous-requête incorrect !');
					return false;
				}
			} else {
				debug('Condition incorrect !');
				return false;
			}
		}

		/**
		 * Ajoute une clause ORDER BY à la requête
		 * @param  String $order champs sur lequel on tri
		 * @return Objet | Bool
		 */
		public function order($order){
			if(is_string($order) && !empty($order)){
				$this->order = $order;
				return $this;
			} else {
				debug('Champ incorrect !');
				return false;
			}
		}

		/**
		 * Ajoute une clause LIMIT à la requête
		 * @param  Int $nbr nombre de ligne
		 * @param  Int $start numero de ligne ou l'on commence (on commence a 0)
		 * @return Objet | Bool
		 */
		public function limit($nbr, $start = 0){
			if(is_int($nbr) || is_string($nbr)){
				if(is_int($start) || is_string($start)){
					if($start !== 0){
						$this->limit = $nbr.' OFFSET '.$start;
					} else {
						$this->limit = $nbr;
					}

					return $this;
				} else {
					debug('Start incorrect !');
					return false;
				}
			} else {
				debug('Nombre incorrect !');
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
					case self::SELECT:
						if(!$this->fields){
							$sql = 'SELECT * FROM '.$this->prefix.$this->table;
						} else {
							$sql = 'SELECT '.(implode(',', $this->fields)).' FROM '.$this->prefix.$this->table;
						}
						break;

					case self::DELETE:
						$sql = 'DELETE FROM '.$this->prefix.$this->table;
						break;

					case self::COUNT:
						$sql = 'SELECT COUNT(*) FROM '.$this->prefix.$this->table;
						break;

					case self::UPDATE:
						$sql = 'UPDATE '.$this->prefix.$this->table;

						if(!empty($this->set)){
							$sql .= ' SET '.(implode(', ', $this->set));
						} else {
							debug('Aucune colonnes à mettre à jour !');
							return false;
						}
						break;

					case self::INSERT:
						$sql = 'INSERT INTO '.$this->prefix.$this->table;

						if(!empty($this->set)){
							$sql .= ' SET '.(implode(', ', $this->set));
						} else {
							debug('Il n\'y a pas de colonne pour effectué l\'insert !');
							return false;
						}
						break;

					default :
						debug('Type de requête inconnu !');
						return false;
				}
				

				if($this->condition){
					$sql .= ' WHERE '.$this->condition;
				}

				if($this->order && $this->type !== self::COUNT){
					$sql .= ' ORDER BY '.$this->order;
				}

				if($this->limit && $this->type !== self::COUNT){
					$sql .= ' LIMIT '.$this->limit;
				}
			} else {
				debug('Table non indiquée, appeler la méthode from() !');
				return false;
			}

			return $sql;
		}

		/**
		 * Retourne les paramètres de la requête
		 * @return Array
		 */
		public function getParams(){
			return $this->params;
		}

		/**
		 * Effectue un SELECT et retourne les résultats
		 * @return Objet | Bool
		 */
		public function execute(){
			$this->type = self::SELECT;
			$sql = $this->getSQL();

			if($sql){
				$this->prepare($sql);
				$this->prepare->execute($this->params);
				
				return $this->hydrate($this->prepare->fetchAll());
			} else {
				return false;
			}
		}

		/**
		 * Effectue un SELECT et retourne le premier résultat
		 * @return Objet | Bool
		 */
		public function first(){
			$this->type = self::SELECT;
			$sql = $this->getSQL();

			if($sql){
				$this->prepare($sql);
				$this->prepare->execute($this->params);

				return $this->hydrate($this->prepare->fetch(), false);
			} else {
				return false;
			}
		}

		/**
		 * Effectue un SELECT et retourne le dernier résultat
		 * @return Objet | Bool
		 */
		public function last(){
			$this->type = self::SELECT;
			$sql = $this->getSQL();

			if($sql){
				$this->prepare($sql);
				$this->prepare->execute($this->params);

				return $this->hydrate(array_pop($this->prepare->fetchAll()), false);
			} else {
				return false;
			}
		}

		/**
		 * Effectue un SELECT et retourne les $nbr premier(s) résultat(s)
		 * @return Objet | Bool
		 */
		public function first_rows($nbr){
			if(is_int($nbr) && $nbr > 0){
				$this->type = self::SELECT;
				$sql = $this->getSQL();

				if($sql){
					$this->prepare($sql);
					$this->prepare->execute($this->params);

					return $this->hydrate(array_slice($this->prepare->fetchAll(), 0, $nbr));
				} else {
					return false;
				}
			} else {
				debug('Nombre incorrect !');
				return false;
			}
		}

		/**
		 * Effectue un SELECT et retourne les $nbr dernier(s) résultats
		 * @return Objet | Bool
		 */
		public function last_rows($nbr){
			if(is_int($nbr) && $nbr > 0){
				$this->type = self::SELECT;
				$sql = $this->getSQL();

				if($sql){
					$this->prepare($sql);
					$this->prepare->execute($this->params);

					return $this->hydrate(array_slice($this->prepare->fetchAll(), '-'.$nbr, $nbr));
				} else {
					return false;
				}
			} else {
				debug('Nombre incorrect !');
				return false;
			}
		}

		/**
		 * Effectue un SELECT et retourne la $num ème ligne
		 * @return Objet | Bool
		 */
		public function row($num){
			if(is_int($num) && $num > 0){
				$this->type = self::SELECT;
				$sql = $this->getSQL();

				if($sql){
					$this->prepare($sql);
					$this->prepare->execute($this->params);

					return $this->hydrate(array_slice($this->prepare->fetchAll(), $num-1, 1), false);
				} else {
					return false;
				}
			} else {
				debug('Numéro de ligne incorrect !');
				return false;
			}
		}

		/**
		 * Effectue un SELECT COUNT(*) et retourne le nombre de lignes selectionnées
		 * @return Int | Bool
		 */
		public function count(){
			$this->type = self::COUNT;
			$sql = $this->getSQL();

			if($sql){
				$this->prepare($sql);
				$this->prepare->execute($this->params);

				return $this->prepare->fetchColumn();
			} else {
				return false;
			}
		}

		/**
		 * Effectue un DELETE et retourne le nombre de lignes effacées
		 * @return Int | Bool
		 */
		public function delete(){
			$this->type = self::DELETE;
			$sql = $this->getSQL();

			if($sql){
				$this->prepare($sql);
				$this->prepare->execute($this->params);

				return $this->prepare->rowCount();
			} else {
				return false;
			}
		}

		/**
		 * Effectue un UPDATE et retourne le nombre de lignes mise à jour
		 * @return Int | Bool
		 */
		public function update(){
			$this->type = self::UPDATE;
			$sql = $this->getSQL();

			if($sql){
				$this->prepare($sql);
				$this->prepare->execute($this->params);

				return $this->prepare->rowCount();
			} else {
				return false;
			}
		}

		/**
		 * Effectue un INSERT
		 * @return Bool
		 */
		public function insert(){
			$this->type = self::INSERT;
			$sql = $this->getSQL();

			if($sql){
				$this->prepare($sql);
				$this->prepare->execute($this->params);

				return $this->db->lastInsertId();
			} else {
				return false;
			}
		}

		/**
		 * On hydrate les données récupérées
		 * @param  Array $result Resultat(s) de la requête
		 * @param  Bool $array Definit si on retourne un tableau ou non
		 * @return Array | Objet | Bool
		 */
		private function hydrate($results, $array = true){
			if(!$results || count($results) <= 0){
				return false;
			} else if(!is_array($results[0]) && !$array) {
				$name = ucfirst($this->table).'_Model';
				$return = new $name($results);
				return $return; 
			} else if(!is_array($results[0])) {
				$name = ucfirst($this->table).'_Model';
				$return = array(new $name($results));
				return $return;
			} else {
				$return = array();
				$name = ucfirst($this->table).'_Model';
				foreach($results as $result){
					$return[] = new $name($result);
				}
				return $return;
			}
		}
	}

?>