<?php 
	
	/**
	 * Classe qui permet d'afficher des popups et d'afficher des erreurs 404
	 * @author Malvyn
	 */
	abstract class Alert {

		/**
		 * Set un message et un type dans la Session flash
		 * @param String $message Message contenu dans l'alert
		 * @param String $type Type du message
		 */
		public static function setFlash($message, $type='success'){
			if(Data::session('flash')){
				$flash = Data::session('flash');
				$flash[] = array(
					'message' 	=> $message,
					'type' 		=> $type
				);
				Data::addSession('flash', $flash);
			} else {
				Data::addSession('flash', array(array(
					'message' 	=> $message,
					'type' 		=> $type
				)));
			}
		}
		
		/**
		 * Retourne le message contenu dans Session flash dans une div de class alert et alert-{type}
		 * @return String
		 */
		public static function flash(){
			if(Data::session('flash')){
				$html = '';
				foreach(Data::session('flash') as $flash){
					$html .= '<div class="alert alert-'.$flash['type'].'"><button type="button" class="close">&times;</button>'.$flash['message'].'</div>';
				}
				Data::deleteSession('flash');
				return $html;
			}
		}

		/**
		 * Informe le navigateur que c'est une page 404,
		 * et y inclut la page 404 avec le message passé en paramètre
		 * @param string $message
		 */
		public static function e404($message){
			header("HTTP/1.0 404 Not Found");
			Controller::set('message', $message);
			Controller::set('title_for_layout', 'Page not found');
			Controller::render(DS.'error'.DS.'404');
			die();
		}
	}

?>