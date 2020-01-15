<?php 

	/**
	 * Contient les données $_POST, $_GET, $_SESSION et $_COOKIE
	 * @author Malvyn
	 */
	abstract class Data {

		/**
		 * Contient les données $_POST
		 * @var Array
		 */
		private static $post = array();

		/**
		 * Contient les données $_GET
		 * @var Array
		 */
		private static $get = array();

		/**
		 * Récupère les données $_POST et $_GET envoyées
		 */
		public static function collect(){
			if(isset($_POST) && !empty($_POST)){
				foreach ($_POST as $key => $value){
					self::$post[htmlspecialchars($key)] = htmlspecialchars($value);
				}
			}

			if(isset($_GET) && !empty($_GET)){
				foreach ($_GET as $key => $value){
					self::$get[htmlspecialchars($key)] = htmlspecialchars($value);
				}
			}

			if(isset(self::$get['page'])){
				if(is_numeric(self::$get['page'])){
					if(self::$get['page'] > 0){
						Request::setPage(self::$get['page']);
					}
				}
			}

			unset($_POST);
			unset($_GET);
		}

		/**
		 * Getter des variables $_POST
		 * @param  String|Int $name Index du tableau $_POST
		 * @return MultiType
		 */
		public static function post($name){
			if(isset(self::$post[$name])){
				return self::$post[$name];
			} else {
				return false;
			}
		}

		/**
		 * Retourne un booléan indiquant l'existance ou non des données $_POST
		 * @return Boolean
		 */
		public static function existPost() {
			if(isset(self::$post) && !empty(self::$post)){
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Getter des variables $_GET
		 * @param  String|Int $name Index du tableau $_GET
		 * @return MultiType
		 */
		public static function get($name){
			if(isset(self::$get[$name])){
				return self::$get[$name];
			} else {
				return false;
			}
		}

		/**
		 * Getter des variables $_SESSION
		 * @param  String|Int $name Index du tableau $_SESSION
		 * @return MultiType
		 */
		public static function session($name){
			if(isset($_SESSION[$name])){
				return $_SESSION[$name];
			} else {
				return false;
			}
		}

		/**
		 * Getter des variables $_COOKIE
		 * @param  String|Int $name Index du tableau $_COOKIE
		 * @return MultiType
		 */
		public static function cookie($name){
			if(isset($_COOKIE[$name])){
				return $_COOKIE[$name];
			} else {
				return false;
			}
		}
		
		/**
		 * Setter des variables $_COOKIE
		 * @param String|Int $name Index du tableau $_COOKIE
		 * @param String $value Valeur pour l'index $name
		 * @param Int $expire Le temps après lequel le cookie expire
		 * @return Bool
		 */
		public static function addCookie($name, $value, $expire = 0){
			if($name != null && $value != null) {
				return setcookie($name, $value, $expire);
			} else {
				return false;
			}

			return true;
		}

		/**
		 * Setter des variables $_SESSION
		 * @param String|Int $name Index du tableau $_SESSION | Tableau à ajouter au tableau $_SESSION
		 * @param MultiType $value Valeur pour l'index $name
		 * @return Bool
		 */
		public static function addSession($name, $value = null){
			if(is_array($name)){
				$_SESSION = array_merge($_SESSION, $name);
			} else if($value != null) {
				$_SESSION[$name] = $value;
			} else {
				return false;
			}

			return true;
		}

		/**
		 * Unset une Session ou toute la Session si $name est null
		 * @param  String|Int $name Index du tableau $_SESSION
		 * @return Bool
		 */
		public static function deleteSession($name = false){
			if($name !== false){
				if(isset($_SESSION[$name])){
					unset($_SESSION[$name]);
					return true;
				} else {
					return false;
				}
			} else {
				unset($_SESSION);
				return true;
			}
		}
	}

?>