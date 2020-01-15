<?php 

	/**
	 * Classe permettant de créer et d'afficher les formulaires ainsi que leurs erreurs
	 * @author Malvyn
	 */
	abstract class Form {

		/**
		 * Contient les erreurs du formulaire
		 * @var Array
		 */
		private static $errors = array();

		/**
		 * Contient le formulaire
		 * @var String
		 */
		private static $form;

		/**
		 * Créer le debut du formulaire
		 * @param String $action 
		 * @param String $method 
		 * @param Array  $options
		 */
		public static function create($action, $method = 'post', $options = array()){
			self::$form = '<form method="'.(($method === 'post' || $method === 'get')? $method : 'post').'" action="'.Router::url($action).'"';

			if(!empty($options)){
				if(is_string($options)){
					self::$form .= ' '.$options;
				} else if(is_array($options)){
					foreach($options as $name => $value){
						self::$form .= ' '.$name.'="'.$value.'"';
					}
				}
			}

			self::$form .= '>';
		}

		/**
		 * Ajout un input dans le formulaire
		 */
		public static function input($name, $label = false, $type = 'text', $options = array()){
			self::$form .= '<div id="input_'.$name.'" class="input_'.$type.'">';

			if(isset(self::$errors[$name])){
				self::$form .= '<div class="error_form">'.self::$errors[$name].'</div>';
			}

			if($label !== false){
				self::$form .= '<label for="'.$name.'"';

				if(isset($options['label'])){
					if(is_string($options['label'])){
						self::$form .= ' '.$options['label'];
					} else if(is_array($options['label'])){
						foreach($options['label'] as $name => $value){
							self::$form .= ' '.$name.'="'.$value.'"';
						}
					}
				}

				self::$form .= '>'.$label.'</label>';
			}

			self::$form .= '<input name="'.$name.'" type="'.$type.'"';

			if(isset($options['input'])){
				if(is_string($options['input'])){
					self::$form .= ' '.$options['input'];
				} else if(is_array($options['input'])){
					foreach($options['input'] as $name => $value){
						self::$form .= ' '.$name.'="'.$value.'"';
					}
				}
			}

			self::$form .= '/></div>';
		}

		/**
		 * Met fin au formulaire et affiche le formulaire
		 */
		public static function end(){
			self::$form .= '</form>';
			echo self::$form;
		}
	}

?>