<?php 

	/**
	 * Classe qui gere toutes les méthodes communes aux contrôleur
	 * @author Malvyn
	 */
	abstract class Controller {

		/**
		 * Contient toutes les variable à inclure dans la page
		 * @var Array
		 */
		private static $vars = array();

		/**
		 * Contient toutes les scripts js et fichier css à inclure dans la page
		 * @var array
		 */
		private static $files = array();
		
		/**
		 * Contient le layout utilisé
		 * @var String
		 */
		public static $layout = 'default';
		
		/**
		 * Indique si le rendu a deja été inclut
		 * @var Bool
		 */
		private static $rendered = false;
		
		/**
		 * Initialise les contrôleurs
		 */
		public function __construct(){
			
		}

		/**
		 * Modifie l'attribut $rendered
		 * @param String $rendered
		 */
		public static function setRendered($rendered){
			self::$rendered = $rendered;
		}

		/**
		 * Modifie l'attribut $layout
		 * @param String $layout
		 */
		public static function setLayout($layout){
			self::$layout = $layout;
		}

		/**
		 * Si le rendu n'a pas déjà été fait, on extrait les variables et on inclut la vue demandé
		 * @param String $view
		 * @return Bool
		 */
		public static function render($view){
			if(self::$rendered)
				return false;
			
			extract(self::$vars);
			
			if(strpos($view, DS) === 0){
				$view = CORE.DS.'Views'.$view.'.php';
			} else {
				$view = CORE.DS.'Views'.DS.Request::controller().DS.$view.'.php';
			}
				
			ob_start();
			@include($view);
			$content_for_layout = ob_get_clean();
			
			$test = @include(CORE.DS.'Views'.DS.'layout'.DS.self::$layout.'.php');
			
			if($test === false){
				echo $content_for_layout;
			}
			
			self::$rendered = true;
			
			return true;
		}

		/**
		 * Si $key est un tableau on le rajoute à $var,
		 * sinon on ajoute une nouvelle case avec la valeur et la clé donnée en paramètre
		 * @param String | Array $key
		 * @param String $value
		 */
		public static function set($key, $value = null){
			if(is_array($key)){
				self::$vars += $key;
			} else {
				self::$vars[$key] = $value;
			}
		}

		/**
		 * Ajoute un fichier js à inclure dans la vue
		 * @param  String $file
		 */
		public static function js($file){
			if(substr($file, 0, 4) == 'http'){
				self::$files['web']['js'][] = $file;
			} else {
				if(substr($file, -3) == '.js'){
					self::$files['local']['js'][] = $file;
				} else {
					self::$files['local']['js'][] = $file.'.js';
				}
			}
		}

		/**
		 * Affiche les balises scripts généré à partir des fichiers js présent dans self::$files
		 */
		public static function getJs(){
			if(!empty(self::$files['web']['js'])){
				foreach(self::$files['web']['js'] as $file){
					echo '<script type="text/javascript" src="'.$file.'"></script>';
				}
			}

			if(!empty(self::$files['local']['js'])){
				foreach(self::$files['local']['js'] as $file){
					echo '<script type="text/javascript" src="'.BASE_URL.'/js/'.$file.'"></script>';
				}
			}
		}

		/**
		 * Ajoute un fichier css à inclure dans la vue
		 * @param  String $file
		 * @param  String $media
		 */
		public static function css($file, $media = null){
			if(substr($file, 0, 4) == 'http'){
				self::$files['web']['css'][] = array(
					'file' => $file,
					'media' => $media
				);
			} else {
				if(substr($file, -4) == '.css'){
					self::$files['local']['css'][] = array(
						'file' => $file,
						'media' => $media
					);
				} else {
					self::$files['local']['css'][] = array(
						'file' => $file.'.css',
						'media' => $media
					);
				}
			}
		}

		/**
		 * Affiche les balises link généré à partir des fichiers css présent dans self::$files
		 */
		public static function getCss(){
			if(!empty(self::$files['web']['css'])){
				foreach(self::$files['web']['css'] as $css){
					echo '<link type="text/css" rel="stylesheet" href="'.$css['file'].'"'.(($css['media'] !== null)? 'media="'.$css['media'].'" ' : ' ').'/>';
				}
			}

			if(!empty(self::$files['local']['css'])){
				foreach(self::$files['local']['css'] as $css){
					echo '<link type="text/css" rel="stylesheet" href="'.BASE_URL.'/css/'.$css['file'].'"'.(($css['media'] !== null)? 'media="'.$css['media'].'" ' : ' ').'/>';
				}
			}
		}
	}

?>