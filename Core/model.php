<?php 
	
	/**
	 * Classe gérant tous les modèles
	 * @author Malvyn
	 */
	abstract class Model {

		/**
		 * Met à jour la ligne s'il elle existe sinon l'ajoute
		 * @return Bool
		 */
		public function save(){
			$table = str_replace('_Model', '', get_class($this));
			$keyName = $this->_primaryKey_();
			$key = $this->$keyName();
			$db = Db::query(static::CONFIG)
					->from($table)
					->set($this);

			if($key !== false){
				return $db->where($keyName.'="'.$key.'"')
							->update();
			} else {
				return $db->insert();
			}
		}

		/**
		 * Créer et met à jour les fichiers modèles
		 * @param  String $bdd Nom de la config a utilisé
		 * @param  Boll $clear Définit si on doit vider le dossier
		 */
		public static function refresh($config, $clear = false){
			if($clear){
				clearDir(CORE.DS.'Models'.DS);
			}
			
			$conf = Config::data($config);

			if($conf){
				$bdd = Db::query($config);
				$tables = $bdd->getTables($conf['database']);
				foreach($tables as $table){
					if(isset($conf['prefix'])){
						$tableName = substr($table, strlen($conf['prefix']));
					} else {
						$tableName = $table;
					}
					
					$fields = $bdd->getFields($table);
					if($fields){
						$name = CORE.DS.'Models'.DS.$tableName.'.php';
						$content = "<?php\r\n\r\n";
						$content .= "/**\r\n";
						$content .= "* Class représentant la table ".$table."\r\n";
						$content .= "* @author Malvyn\r\n";
						$content .= "*/\r\n";
						$content .= "class ".ucfirst($tableName)."_Model extends Model {\r\n\r\n";
						$content .= "\t/**\r\n";
						$content .= "\t* Nom de la configuration utilisé par le modèle\r\n";
						$content .= "\t* @var String\r\n";
						$content .= "\t*/\r\n";
						$content .= "\tconst CONFIG = '".$config."';\r\n\r\n";

						$get = "";
						foreach($fields as $field){
							$access = 'public';
							$content .= "\t/**\r\n";
							$content .= "\t* @var ".$field['Type']."\r\n";
							$content .= "\t* @null ".$field['Null']."\r\n";

							if(isset($field['Key']) && !empty($field['Key'])){
								$content .= "\t* @key ".$field['Key']."\r\n";
								if($field['Key'] === 'PRI'){
									$access = 'private';

									$get .= "\r\n\t/**\r\n";
									$get .= "\t* Getter de l'attribut $".$field['Field']."\r\n";
									$get .= "\t* @return ".$field['Type']."\r\n";
									$get .= "\t*/\r\n";
									$get .= "\tpublic function ".$field['Field']."(){\r\n";
									$get .= "\t\treturn \$this->".$field['Field'].";\r\n";
									$get .= "\t}\r\n";

									$key = $field['Field'];
								}
							}

							if(isset($field['Extra']) && !empty($field['Extra'])){
								$content .= "\t* @options ".$field['Extra']."\r\n";
							}

							if(isset($field['Default']) && !empty($field['Default'])){
								$content .= "\t* @default ".$field['Default']."\r\n";
							}

							$content .= "\t*/\r\n";
							$content .= "\t".$access." $".$field['Field']." = false;\r\n\r\n";
						}

						if(isset($key)){
							$content .= "\t/**\r\n";
							$content .= "\t* Nom de la clé primaire\r\n";
							$content .= "\t* @var String\r\n";
							$content .= "\t*/\r\n";
							$content .= "\tprivate \$_primaryKey_ = '".$key."';\r\n\r\n";
						}

						$content .= "\r\n\t/**\r\n";
						$content .= "\t* Hydrate les données\r\n";
						$content .= "\t* @param \$data Données\r\n";
						$content .= "\t*/\r\n";
						$content .= "\tpublic function __construct(\$data = null){\r\n";
						$content .= "\t\tif(\$data != null){\r\n";

						foreach($fields as $field){
							$content .= "\t\t\t\$this->".$field['Field']." = (isset(\$data['".$field['Field']."']))? \$data['".$field['Field']."'] : false;\r\n";
						}

						$content .= "\t\t}\r\n";
						$content .= "\t}\r\n";
						$content .= $get;

						if(isset($key)){
							$content .= "\r\n\t/**\r\n";
							$content .= "\t* Getter de l'attribut \$_primaryKey_\r\n";
							$content .= "\t* @return String\r\n";
							$content .= "\t*/\r\n";
							$content .= "\tpublic function _primaryKey_(){\r\n";
							$content .= "\t\treturn \$this->_primaryKey_;\r\n";
							$content .= "\t}\r\n";
						}

						$content .= "}\r\n\r\n";
						$content .= "?>";

						file_put_contents($name, $content);
					}
				}
			}
		}
	}

?>