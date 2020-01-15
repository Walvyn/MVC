<?php

	/**
	* Affiche la donnée passée en paramètre
	* @param MultiType $var
	* @param Int $die
	*/
	function debug($var, $die = 0){
		if(Config::debug() > 0){
			$debug = debug_backtrace();

			echo '<p>&nbsp;</p><p><a href="#" onclick="$(this).parent().next(\'ol\').slideToggle(); return false"><strong>'
					.$debug[0]['file'].'</strong> - Line : '.$debug['0']['line'].'</a></p>';
			echo '<ol style="display:none">';
			
			foreach ($debug as $k => $v){
				if($k > 0){
					echo '<li><strong>'.$v['file'].'</strong> - Line : '.$v['line'].'</li>';
				}
			}
			
			echo '</ol><pre>';
			print_r($var);
			echo '</pre>';
		
			if($die > 0){
				exit;
			}
		}
	}

	/**
	 * Effectue une redirection
	 * @param String $url
	 * @param Int $code
	 */
	function redirect($url='', $code = null){
		if($code == 301){
			header("HTTP/1.1 301 Moved Permanently");
		}

		if(!preg_match('#www|http#', $url)){
			header("location: ".Router::url($url));
		} else {
			header("location: ".$url);
		}

		exit;
	}

	/**
	 * Supprime tous les fichiers d'un dossier
	 * @param  String $path Chemin du dossier
	 * @return Bool
	 */
	function clearDir($path){
		// On définit le répertoire dans lequel on souhaite travailler
		$repertoire = opendir($path);
		  
		while (false !== ($fichier = readdir($repertoire))){
			// On définit le chemin du fichier à effacer
			$chemin = $path.DS.$fichier;
			  
			// Si le fichier n'est pas un répertoire…
			if($fichier != ".." AND $fichier != "." AND !is_dir($fichier)){
				// On efface
		       	unlink($chemin);
		    }
		}

		closedir($repertoire);

		return true;
	}

?>