<?php
	function addJSONContent($file, $array){
		$file_content = file_get_contents($file);
		$content = json_decode($file_content, true); 
		array_push($content, $array);
		file_put_contents($file,json_encode($content));
		return 0;
	}

	function readJSONContent($file){
		$file_content = file_get_contents($file);
		$content = json_decode($file_content, true); 
		return $content;
	}

	//EXP UTILISATION

	//$tab = readJSONContent("users.json");
	//print($tab[0]["role"]);
	//addJSONContent("users.json", array("nom"=>"Manchec","prenom"=>"Sergueï", "id"=>"54765", "mdp"=>"a", "role"=>"a", "naissance"=>"12/06/2006", "genre"=>"M", "tel"=>"0767239623", "courriel"=>"serguei.manchec@gmail.com"));
?>
