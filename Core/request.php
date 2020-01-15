<?php

	/**
	 * Cette classe contient le requete effectué par l'utilisateur
	 * @author Malvyn
	 *
	 */
	abstract class Request {

		/**
		 * Contient l'url entré dans la barre d'adresse
		 * @var string
		 */
		private static $showUrl;
		
		/**
		 * Contient l'url parsé
		 * @var string
		 */
		private static $url;
		
		/**
		 * Contient le nom du controlleur demandé
		 * @var string
		 */
		private static $controller;
		
		/**
		 * Contient nom de l'action demandé
		 * @var strings
		 */
		private static $action;
		
		/**
		 * Contient les paramètres demandés
		 * @var array
		 */
		private static $params;
		
		/**
		 * Contient le numero de la page
		 * @var int
		 */
		private static $page = 1;
		
		/**
		 * @var string
		 */
		private static $prefix = false;
		
		public static function showUrl(){ return self::$showUrl; }

		public static function url(){ return self::$url; }

		public static function urlWithoutParameters(){
			return str_replace(self::$params, '', self::$url); 
		}
		
		public static function controller(){ return self::$controller; }
		
		public static function action(){ return self::$action; }
		
		public static function params(){ return self::$params; }
		
		public static function prefix(){ return self::$prefix; }
		
		public static function page(){ return self::$page; }

		public static function setShowUrl($u){
			self::$showUrl = $u;
		}
		
		public static function setUrl($u){
			self::$url = $u;
		}
		
		public static function setController($c){
			self::$controller = $c;
		}
		
		public static function setAction($a){
			self::$action = $a;
		}
		
		public static function setParams($p){
			self::$params = $p;
		}
		
		public static function setPrefix($p){
			self::$prefix = $p;
		}
		
		public static function setPage($p){
			self::$page = $p;
		}
	}

?>