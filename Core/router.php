<?php

	/**
	 * Cette classe gère les urls d'entrées ou de sorties du site
	 * @author Malvyn
	 */
	abstract class Router {

		/**
		 * Contrôleur par défaut
		 */
		const PAGE = 'home';

		/**
		 * Méthode par défaut
		 */
		const ACTION = 'index';

		/**
		 * Contient les routes definies dans le fichier de configuration
		 * @var Array
		 */
		private static $routes = array();

		/**
		 * Contient les prefixes, permettant de remplacer par ex: (boss par admin)
		 * @var Array
		*/
		private static $prefixes = array();

		/**
		 * Parse l'url por definir le contrôleur, la méthode et les paramètres
		 * @return Bool
		 */
		public static function parse(){
			$url = isset($_SERVER['PATH_INFO'])? trim($_SERVER['PATH_INFO'], '/') : '/';
			if(strpos($url, '?')){
				$url = substr($url, 0, strpos($url, '?'));
			}

			Request::setShowUrl($url);

			$url = trim($url, '/');
			$url = explode('/', $url);

			//Gestion des Prefix
			if(in_array($url[0], array_keys(self::$prefixes))){
				Request::setPrefix(self::$prefixes[$url[0]]);
				array_shift($url);
			}

			if(count($url) > 1){
				$base = $url[0].'/'.Request::prefix().'_'.$url[1];
				for($i=2; $i < count($url); $i++){ 
					$p[] = $url[$i];
				}
			} else if(count($url) == 1) {
				$base = $url[0].'/'.Request::prefix().'_'.self::ACTION;
			} else {
				$base = self::PAGE.'/'.Request::prefix().'_'.self::ACTION;
			}

			if(in_array($base, array_keys(self::$routes))){
				foreach(self::$routes[$base] as $key => $value){
					if(!is_array($value)){
						$link[] = $value;
					} else if(isset($p[0])){
						$link[] = $p[0];
						array_shift($p);
					}
				}

				redirect(implode('/', $link));
			}

			foreach(self::$routes as $key => $value){
				$ok = true;
				foreach($value as $v){
					if(is_array($v)){
						$ok = false;
						break;
					}
				}

				if($ok){
					$compare = implode('/', $value);
					if($compare == implode('/', $url)){
						$url = explode('/', $key);
						break;
					}
				} else {
					$ok = true;
					if(count($url) <= count($value)){
						foreach ($url as $num => $piece) {
							if(preg_match('#[-a-zA-Z_\.:;!,]+#', ((!isset($value[$num]['prefix']))? $piece : str_replace($value[$num]['prefix'], '', $piece)))){
								$type[$num]['s'] = true;
							} else {
								$type[$num]['s'] = false;
							}

							if(preg_match('#[-0-9_\.:;!,]#', ((!isset($value[$num]['prefix']))? $piece : str_replace($value[$num]['prefix'], '', $piece)))){
								$type[$num]['i'] = true;
							} else {
								$type[$num]['i'] = false;
							}
						}

						foreach ($value as $k => $v) {
							if(!is_array($v)){
								if(isset($url[$k])){
									if($v !== $url[$k]){
										$ok = false;
										break;
									}
								} else {
									$ok = false;
									break;
								}
							} else {
								if(!isset($url[$k])){
									if(!$v['n']){
										if(!isset($v['default'])){
											$ok = false;
											break;
										}
									}
								} else {
									if($type[$k]['s']){
										if(!$v['s']){
											$ok = false;
											break;
										} else {
											if($type[$k]['i']){
												if(!$v['i']){
													$ok = false;
													break;
												}
											}
										}
									} else {
										if($type[$k]['i']){
											if(!$v['i']){
												$ok = false;
												break;
											}
										} else {
											$ok = false;
											break;
										}
									}
								}
							}
						}

						if($ok){
							foreach($value as $k => $v){
								if(is_array($v)){
									if(isset($url[$k])){
										if(!$v['prefix']){
											$params[] = $url[$k];
										} else {
											$params[] = str_replace($v['prefix'], '', $url[$k]);
										}
									} else if(isset($v['default'])){
										$params[] = $v['default'];
									}
								}
							}

							$url = explode('/', $key);
							if(isset($params)){
								$url = array_merge($url, $params);
							}
							break;
						}
					}
				}
			}

			Request::setUrl(implode('/', $url));
			
			if(!isset($o)){
				if(isset($url[0]) && $url[0] != ''){
					Request::setController($url[0]);
					array_shift($url);
						
					if(isset($url[0]) && $url[0] != ''){
						Request::setAction((Request::prefix() === false)? $url[0] : Request::prefix().'_'.$url[0]);
						array_shift($url);
					}
				} else {
					Request::setController(self::PAGE);
				}

				if(Request::action() == null){
					Request::setAction((Request::prefix() === false)? self::ACTION : Request::prefix().'_'.self::ACTION);
				}
					
				foreach (self::$prefixes as $k=>$v){
					if(strpos(Request::action(), $v.'_') === 0){
						Request::setPrefix($v);
					}
				}
					
				Request::setParams($url);
			}
			
			return true;
		}

		/**
		 * Ajoute un prefix
		 * @param String $url
		 * @param String $prefix
		 */
		public static function prefix($url, $prefix){
			self::$prefixes[$url] = $prefix;
		}

		/**
		 * Ajoute une réecriture ou une redirection
		 * @param String $redir
		 * @param String $url
		 */
		public static function connect($redir, $url){
			$param = false;
			$null = false;
			$default = false;
			$url = trim($url, '/');
			$link = explode('/', $url);
			$url = array();

			foreach($link as $key => $value){
				if(strpos($value, '{') !== false){
					$param = true;
					$prefix = substr($value, 0, strpos($value, '{'));
					$value = substr($value, strlen($prefix)+1, strlen($value)-strlen($prefix)-2);
					$value = explode('+', $value);
					$url[] = array('s' => false, 'i' => false, 'n' => false, 'prefix' => ((strlen($prefix) === 0)? false : $prefix));
					foreach($value as $v){
						switch ($v){
							case 's':
								$url[$key]['s'] = true;
								break;

							case 'i':
								$url[$key]['i'] = true;
								break;

							case 'n':
								if(!isset($url[$key]['default'])){
									$url[$key]['n'] = true;
									$null = true;
								}
								break;

							default:
								if(strpos($v, '[') === 0 && !$null){
									$url[$key]['default'] = substr($v, 1, strlen($v)-2);
									$default = true;
								}
						}
					}

					if($url[$key]['n'] === false){
						if($null){
							$url = array_splice($url, 0, -1);
							debug('Incohérence dans le routage '.$redir.' au niveau des possibilités de null');
						} else if($default && !isset($url[$key]['default'])){
							$url = array_splice($url, 0, -1);
							debug('Incohérence dans le routage '.$redir.' au niveau des variables par défaut');
						}
					}
				} else if(!$param){
					$url[] = $value;
				}
			}

			$redir = explode('/', $redir);
			if(count($redir) > 1){
				$r = $redir[0].'/'.$redir[1];
			} else if(count($redir) == 1) {
				$r = $redir[0].'/'.self::ACTION;
			} else {
				$r = self::PAGE.'/'.self::ACTION;
			}

			self::$routes[$r] = $url;
		}

		/**
		 * Converti l'url selon les prefix et redirections defini
		 * @param String $url
		 * @param Bool $http
		 * @return String
		 */
		public static function url($url, $http = false){
			$url = explode('?', $url);
			$get = '';

			if(isset($url[1])){
				$get = $url[1];
			}

			$url = $url[0];
			$url = trim($url, '/');

			if(!empty($url)){
				$link = explode('/', $url);
			} else {
				$link = null;
			}

			//Gestion des prefix
			$prefix = array_search($link[0], self::$prefixes);
			if($prefix !== false){
				$prefix = '/'.$prefix;
				array_shift($link);
			} else {
				$prefix = '';
			}

			$params = array();
			if(count($link) > 1){
				$base = $link[0].'/'.$link[1];
				for($i=2; $i < count($link); $i++){ 
					$params[] = $link[$i];
				}
			} else if(count($link) == 1) {
				$base = $link[0].'/'.self::ACTION;
			} else {
				$base = self::PAGE.'/'.self::ACTION;
			}

			if(in_array($base, array_keys(self::$routes))){
				foreach ($params as $num => $piece) {
					if(preg_match('#[-a-zA-Z_\.:;!,]+#', $piece)){
						$type[$num]['s'] = true;
					} else {
						$type[$num]['s'] = false;
					}

					if(preg_match('#[-0-9_\.:;!,]#', $piece)){
						$type[$num]['i'] = true;
					} else {
						$type[$num]['i'] = false;
					}
				}

				$link = array();
				$ok = true;
				foreach(self::$routes[$base] as $key => $value){
					if(!is_array($value)){
						$link[] = $value;
					} else {
						if(!isset($params[0])){
							if(!$value['n']){
								if(!isset($value['default'])){
									$ok = false;
									break;
								} else {
									if(!$value['prefix']){
										$link[] = $value['default'];
									} else {
										$link[] = $value['prefix'].$value['default'];
									}							
								}
							}
						} else {
							if($type[0]['s']){
								if(!$value['s']){
									$ok = false;
									break;
								} else {
									if($type[0]['i']){
										if(!$value['i']){
											$ok = false;
											break;
										}
									}
								}
							} else {
								if($type[0]['i']){
									if(!$value['i']){
										$ok = false;
										break;
									}
								} else {
									$ok = false;
									break;
								}
							}

							if(!$value['prefix']){
								$link[] = $params[0];
							} else {
								$link[] = $value['prefix'].$params[0];
							}

							array_shift($params);
							array_shift($type);
						}
					}
				}

				if($ok){
					$url = implode('/', $link);
					if(!empty($get)){
						$url .= '?'.$get;
					}

					if(!$http){
						return BASE_URL.$prefix.'/'.$url;
					} else {
						return DOMAIN.BASE_URL.$prefix.'/'.$url;
					}
				}
			}

			if(!empty($get)){
				$url .= '?'.$get;
			}

			if(!$http){
				return BASE_URL.$prefix.'/'.$url;
			} else {
				return DOMAIN.BASE_URL.$prefix.'/'.$url;
			}
		}
	}

?>