<?php 

	/**
	* Contient toutes les configurations du MVC
	* @author Malvyn
	*/
	abstract class Config {

		/**
		 * Active le mode debug ou non
		 * @var Int
		 */
		private static $debug = 1;

		/**
		 * Contient toutes les configurations pour la connexion de la base de données
		 * @var Array
		 */
		private static $databases = array(
				'default' => array(
						'base'		=> 'mysql',
						'host'		=> '127.0.0.1',
						'database' 	=> 'festybdd',
						'login'		=> 'root',
						'password'	=> '',
						'prefix'	=> '',
						'params'	=> array(1002 => 'SET NAMES utf8')
				),
				'second' => array(
						'base'		=> 'mysql',
						'host'		=> '127.0.0.1',
						'database' 	=> 'mark_champ',
						'login'		=> 'root',
						'password'	=> '',
						'prefix'	=> 'app_',
						'params'	=> array(1002 => 'SET NAMES utf8')
				),
				/*'name' => array(
						'base'		=> 'type de base',
						'host'		=> 'adresse de la base',
						'database' 	=> 'nom de la base',
						'login'		=> 'login',
						'password'	=> 'mot de passe',
						'prefix'	=> 'préfix des tables',
						'params'	=> array(paramètres)
				),*/
		);

		/**
		 * Retourne la configuration demandée
		 * @param String $name Nom de la config à récupérer
		 * @return Array
		*/
		public static function data($name){
			if(isset(self::$databases[$name])){
				return self::$databases[$name];
			} else {
				debug('Configuration introuvable !');
				return false;
			}
		}

		/**
		 * Retourne si le mode debug est actif ou non
		 * @return Int
		 */
		public static function debug(){
			return self::$debug;
		}
		
		/**
		 * Active ou desactive le mode debug
		 * @param Int $debug Etat du debug
		 */
		public static function setDebug($debug){
			if(is_int($debug)){
				self::$debug = $debug;
			}
		}
		 
	}

?>