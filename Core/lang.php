<?php

/**
 * Gère les langues du site
 * @author Malvyn
 */
abstract class Lang {
	
	/**
	 * Tableau contenant les textes du site dans la langue choisie
	 * @var Array
	 */
	private static $texts = array();

	/**
	 * Récupère les textes php et js du site
	 */
	public static function init(){
		if(file_exists(ROOT.DS.'Lang'.DS.LANG.DS.'global.lang')){
			Lang::sortFile(ROOT.DS.'Lang'.DS.LANG.DS.'global.lang');
			self::$texts = get_object_vars(json_decode(file_get_contents(ROOT.DS.'Lang'.DS.LANG.DS.'global.lang')));
		}

		if(file_exists(ROOT.DS.'Lang'.DS.LANG.DS.'js.lang')){
			Lang::sortFile(ROOT.DS.'Lang'.DS.LANG.DS.'js.lang');
			self::$texts += get_object_vars(json_decode(file_get_contents(ROOT.DS.'Lang'.DS.LANG.DS.'js.lang')));
		}
	}
	
	/**
	 * Retourne le texte pour le mot clé fournit
	 * @param $var Mot clé du texte à récupérer
	 * @param $data Tableau contenant les variables dynamiques du texte ($data[name])
	 * @return String
	 */
	public static function trad($var, $data = null){
		if(isset(self::$texts[$var])){
			if($data != null){
				return eval('return "'.self::$texts[$var].'";');
			} else {
				return self::$texts[$var];
			}
		}
	}
	
	public static function js(){
		return file_get_contents(ROOT.DS.'Lang'.DS.LANG.DS.'js.lang');
	}

	private static function sortFile($file){
		$tab = get_object_vars(json_decode(file_get_contents($file)));
	 	$keys = array_keys($tab);
	 	sort($keys);
		foreach($keys as $key) {
			$final[$key] = $tab[$key];
		}

		$string = preg_replace_callback('/\\\\u([0-9a-zA-Z]{4})/', function($matches) {
				return mb_convert_encoding(pack('H*',$matches[1]),'UTF-8','UTF-16');
			}, json_encode($final)
		);

		$string = str_replace('",', "\",\n\t", $string);
		$string = str_replace('}', "\n}", $string);
		$string = str_replace('{', "{\n\t", $string);
		//debug($string);
		file_put_contents($file, $string);
	}
}

?>