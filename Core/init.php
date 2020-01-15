<?php

	require('functions.php');
	
	/**
	 * Charge toutes les classes
	 * @param String $classname
	 */
	function autoload($classname){
		$paths = array(
				'_Model'		=>	CORE.DS.'Models'.DS,
				'_Controller'	=>	CORE.DS.'Controllers'.DS,
				'' 				=>	CORE.DS
		);
	
		foreach($paths as $name => $chemin){
			if(!is_array($chemin)){
				$chemin = array($chemin);
			}

			foreach($chemin as $location){
				if(preg_match('#(.*)'.$name.'$#i', $classname, $out)){
					$filepath = $location.strtolower($out[1]).'.php';
					$r = @include_once($filepath);
					if($r==true || class_exists($classname, false)){
						return;
					}
				}
			}
		}
	}
	
	spl_autoload_register('autoload');
	
	Config::setDebug(($_SERVER['SERVER_ADDR'] == '::1' || $_SERVER['SERVER_ADDR'] == '127.0.0.1' || $_SERVER['SERVER_ADDR'] == 'localhost')? 1 : 0 );

	if(Config::debug() < 1){
		ini_set('display_errors','0');
	}

	if(Data::session('lang')){
		define('LANG', Data::session('lang'));
	} else {
		$ext = substr($_SERVER['SERVER_NAME'], stripos($_SERVER['SERVER_NAME'], '.')+1);
		if($ext == 'com'){
			define('LANG', LANG_DEFAULT);
		} else {
			define('LANG', $ext);
		}
	}

	require(ROOT.DS.'Config'.DS.'global.php');
	require(ROOT.DS.'Config'.DS.'router.php');
?>