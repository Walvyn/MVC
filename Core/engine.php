<?php 

	/**
	 * Classe gérant les connexions
	 * @author Malvyn
	 */
	abstract class Engine {

		/**
		 * Nom de la config de la base de données
		 * @var String
		 */
		protected $config;

		/**
		 * Contient les connexions à la base de données 
		 * @var Array
		 */
		protected static $connections = array();

		/**
		 * Contient le type de la requête
		 * @var Int
		 */
		protected $type = false;

		/**
		 * Nom de la table
		 * @var Bool | String
		 */
		protected $table = false;

		/**
		 * Contient la connexion de la base de données
		 * @var Objet PDO
		 */
		protected $db;

		/**
		 * Contient la requête préparé
		 * @var Objet PDO
		 */
		protected $prepare = false;

		/**
		 * Contient la dernière requête
		 * @var Objet PDO
		 */
		protected $sql = false;

		/**
		 * Prefix utilisé pour les tables
		 * @var String
		 */
		protected $prefix = '';

		/**
		 * Etablie une connexion avec la base de données
		 * @param $config Nom de la config à utiliser
		 * @return Bool
		 */
		public function __construct($config = 'default'){
			$this->config = $config;
			$conf = Config::data($this->config);
			
			if($conf){
				if(isset($conf['prefix'])){
					$this->prefix = $conf['prefix'];
				}
				
				//Connexion à la base
				if(isset(static::$connections[$this->config])){
					$this->db = static::$connections[$this->config];
					return true;
				}
				
				try {
					$pdo = new PDO($conf['base'].':host='.$conf['host'].';dbname='.$conf['database'].';', 
									$conf['login'], 
									$conf['password'], 
									$conf['params']);
					$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
					
					static::$connections[$this->config] = $pdo;
					$this->db = $pdo;
				} catch(PDOException $error) {
					if(Config::debug() >= 1){
						debug($error->getMessage(), 1);
					} else {
						die('Impossible de se connecter à la base de données !');
					}
				}
			}
		}
		
		/**
		 * Prépare la requête
		 * @var $sql Requête SQL
		 */
		protected function prepare($sql){
			//Si aucune requête n'est stocké ou si elle est différente de celle passée en paramètre
			if(!$this->sql || $sql !== $this->sql){
				//alors on prépare la requête et on la stock
				$this->sql = $sql;
				$this->prepare = $this->db->prepare($this->sql);
			}
		}

		/**
		 * Réupère les tables de la base de données $bdd
		 * @param  String $bdd Nom de la base
		 * @return Array
		 */
		public function getTables($bdd){
			$query = $this->db->query('SHOW TABLES');
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
	        	$tables[] = $row['Tables_in_'.$bdd];
	   	 	}
			return $tables;
		}

		/**
		 * Réupère les champs de la table $table
		 * @param  String $table Nom de la table
		 * @return Array
		 */
		public function getFields($table){
			if($table){
				$query = $this->db->query('SHOW COLUMNS FROM '.$table);
				$fields = $query->fetchAll(PDO::FETCH_ASSOC);
				foreach($fields as $field){
					$colunms[] = $field;
				}
				return $colunms;
			} else {
				return false;
			}
		}
	}