<?php

	//chemin vers le dossier Content
	define('CONTENT', dirname(__FILE__));
	
	//chemin de la racine
	define('ROOT', dirname(CONTENT));
	
	//separateur (/ ou \)
	define('DS', DIRECTORY_SEPARATOR);
	
	//chemin vers le dossier Core
	define('CORE', ROOT.DS.'Core');
	
	//chemin vers le dossier à partir du serveur
	define('BASE_URL', (dirname(dirname($_SERVER['SCRIPT_NAME'])) == DS)? '' : dirname(dirname($_SERVER['SCRIPT_NAME'])));

	header("HTTP/1.0 200 OK");
	
	session_start();
	
	//On inclut toutes les classes avec include.php
	require(CORE.DS.'init.php');

	if(Config::debug() > 0){
	    $start = microtime(true);
	}

	//Récupère les données $_POST et $_GET
	Data::collect();
	
	//On parse l'url fournit pour definir le contrôleur ainsi que la méthode et les paramètres
	Router::parse();

	//Met à jour les modèles
	Model::refresh('default', true);

	//debug($_SESSION);
	
	require(ROOT.DS.'Config'.DS.'controller.php');
	
	$class = ucfirst(Request::controller()).'_Controller';
	
	//on fait les différents tests sur le contrôleur et la méthode appelée
	if(!class_exists($class))
		Alert::e404('Le page '.Request::showUrl().' n\'existe pas !');
	
	$o = new $class();
	
	if(!in_array(Request::action(), array_diff(get_class_methods($o), get_class_methods('Controller'))))
		Alert::e404('Le page '.Request::showUrl().' n\'existe pas !');
	
	$type = new ReflectionMethod($class, Request::action());
	if($type->isPrivate())
		Alert::e404('La page '.Request::showUrl().' n\'existe pas !');
	
	//On appelle l'action avec les paramètres
	call_user_func_array(array($o, Request::action()), Request::params());
	
	//On affiche le rendu
	Controller::render(Request::action());
	
	if(Config::debug() > 0){
		?>
	    <div style="bottom:0; background:#900; text-decoration:none; line-height:30px; height:30px; right:0; padding-left: 10px; position: fixed;">
		  <?php echo '<a style="color:#FFF;" href="#" onclick="$(this).parent().slideToggle(); return false">Page généré en '.round(microtime(true) - $start, 5).' secondes&nbsp</a>'; ?>
	    </div>
		<?php
	}

?>