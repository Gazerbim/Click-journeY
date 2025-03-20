<?php
	function ajouterContenuJson($file, $array){
		$file_content = file_get_contents($file);
		$content = json_decode($file_content, true); 
		array_push($content, $array);
		file_put_contents($file,json_encode($content, JSON_PRETTY_PRINT));
		return 0;
	}

	function lireFichierJson($file){
		$file_content = file_get_contents($file);
		$content = json_decode($file_content, true); 
		return $content;
	}

	function trouverUtilisateurAvecId($user_tab, $id){ # Retourne la ligne avec l'id correspondant dans le tableau (0 si non trouvé). Ca commence à 1
		foreach ($user_tab as $key => $value) {
			if($value["id"] == $id){
				return $key+1;
			}
		}
		return 0;
	}

	function estValideMDP($mdp, $hash){
		if (password_verify($mdp, $hash)) {
			return true;
		} else {
			return false;
		}
	}

	function modifierNomUtilisateur($id, $nouveauNom){
		$content = lireFichierJson("databases/users.json");
		$line = trouverUtilisateurAvecId($content, $id);
		if($line != 0){
			$content[$line-1]["nom"] = $nouveauNom;
			file_put_contents("databases/users.json",json_encode($content, JSON_PRETTY_PRINT));
			return 0;
		}
	}

	function verifCourriel($courriel){
		$file_content = file_get_contents("databases/users.json");
		$content = json_decode($file_content, true);
		foreach ($content as $key => $value) {
			if($value["courriel"] == $courriel){
				return false;
			}
		}
		return true;
	}

	function modifierPrenomUtilisateur($id, $nouveauPrenom){
		$content = lireFichierJson("databases/users.json");
		$line = trouverUtilisateurAvecId($content, $id);
		if($line != 0){
			$content[$line-1]["prenom"] = $nouveauPrenom;
			file_put_contents("databases/users.json",json_encode($content, JSON_PRETTY_PRINT));
			return 0;
		}
	}

	function modifierCourrielUtilisateur($id, $nouveauCourriel){
		$content = lireFichierJson("databases/users.json");
		$line = trouverUtilisateurAvecId($content, $id);
		if($line != 0){
			$content[$line-1]["courriel"] = $nouveauCourriel;
			file_put_contents("databases/users.json",json_encode($content, JSON_PRETTY_PRINT));
			return 0;
		}
	}
	
	function modifierRoleUtilisateur($id, $nouveauRole){
		$content = lireFichierJson("databases/users.json");
		$line = trouverUtilisateurAvecId($content, $id);
		if($line != 0){
			$content[$line-1]["role"] = $nouveauRole;
			file_put_contents("databases/users.json",json_encode($content, JSON_PRETTY_PRINT));
			return 0;
		}
	}

	function modifierMotDePasseUtilisateur($id, $nouveauMdp){
		$content = lireFichierJson("databases/users.json");
		$line = trouverUtilisateurAvecId($content, $id);
		if($line != 0){
			$content[$line-1]["mdp"] = password_hash($nouveauMdp, PASSWORD_BCRYPT);
			file_put_contents("databases/users.json",json_encode($content, JSON_PRETTY_PRINT));
			return 0;
		}
	}

	function modifierNaissanceUtilisateur($id, $nouvelleNaissance){
		$content = lireFichierJson("databases/users.json");
		$line = trouverUtilisateurAvecId($content, $id);
		if($line != 0){
			$content[$line-1]["naissance"] = $nouvelleNaissance;
			file_put_contents("databases/users.json",json_encode($content, JSON_PRETTY_PRINT));
			return 0;
		}
	}

	function modifierGenreUtilisateur($id, $nouveauGenre){
		$content = lireFichierJson("databases/users.json");
		$line = trouverUtilisateurAvecId($content, $id);
		if($line != 0){
			$content[$line-1]["genre"] = $nouveauGenre;
			file_put_contents("databases/users.json",json_encode($content, JSON_PRETTY_PRINT));
			return 0;
		}
	}

	function modifierTelUtilisateur($id, $nouveauTel){
		$content = lireFichierJson("databases/users.json");
		$line = trouverUtilisateurAvecId($content, $id);
		if($line != 0){
			$content[$line-1]["tel"] = $nouveauTel;
			file_put_contents("databases/users.json",json_encode($content, JSON_PRETTY_PRINT));
			return 0;
		}
	}

	function rrmdir($dir) {
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (filetype($dir."/".$object) == "dir") rmdir($dir."/".$object); else unlink($dir."/".$object);
				}
			}
			reset($objects);
			rmdir($dir);
		}
	}

	function supprimerUtilisateur($id){
		$content = lireFichierJson("databases/users.json");
		$line = trouverUtilisateurAvecId($content, $id);
		rrmdir("databases/utilisateurs/".$id);
		if($line != 0){
			unset($content[$line-1]);
			file_put_contents("databases/users.json",json_encode($content, JSON_PRETTY_PRINT));
			return 0;
		}
	}

	supprimerUtilisateur(49385);

	function recupereInfosUtilisateur($id){
		$content = lireFichierJson("databases/users.json");
		$line = trouverUtilisateurAvecId($content, $id);
		if($line != 0){
			return $content[$line-1];
		}
	}

	function existeUtilisateur($id){
		$content = lireFichierJson("databases/users.json");
		$line = trouverUtilisateurAvecId($content, $id);
		if($line != 0){
			return 1;
		}
		return 0;
	}

	function checkIdentifiants($courriel, $mdp){
		$file_content = file_get_contents("databases/users.json");
		$content = json_decode($file_content, true);
		foreach ($content as $key => $value) {
			if($value["courriel"] == $courriel && estValideMDP($mdp, $value["mdp"])){
				return true;
			}
		}
		return false;
	}

	function recupererVoyagesUtilisateur($id){
		$path = "databases/utilisateurs/".$id."/voyages.json";
		$file_content = file_get_contents($path);
		$content = json_decode($file_content, true);
		return $content;
	}

	
	

	function creerVoyage($nom, $description, $tarif, $debut, $fin, $etapes){
		$id = creeId();
		$path = "databases/voyages.json";
		$tab = lireFichierJson($path);
		$tab[] = array("nom"=>$nom, "description"=>$description, "etapes"=>$etapes, "tarif"=>$tarif, "debut"=>$debut, "fin"=>$fin);
		mkdir("databases/voyages/".$id);
		file_put_contents("databases/voyages/".$id."voyage.txt","");
		file_put_contents($path, json_encode($tab, JSON_PRETTY_PRINT));
	}

	function estIdValide($id){
		$file_content = file_get_contents("databases/users.json");
		$content = json_decode($file_content, true);
		foreach ($content as $key => $value) {
			if($value["id"] == $id){
				return false;
			}
		}
		return true;
	}

	function creeId(){
		$id = rand(10000, 99999);
		while(!estIdValide($id)){
			$id = rand(10000, 99999);
		}
		return $id;
	}

	function ajouterUtilisateur($nom, $prenom, $mdp, $role, $naissance, $genre, $tel, $courriel){
		$id = creeId();
		$path = "databases/users.json";
		$tab = lireFichierJson($path);
		$tab[] = array("nom"=>$nom, "prenom"=>$prenom, "id"=>$id, "mdp"=>password_hash($mdp, PASSWORD_BCRYPT), "role"=>$role, "naissance"=>$naissance, "genre"=>$genre, "tel"=>$tel, "courriel"=>$courriel);
		file_put_contents($path, json_encode($tab, JSON_PRETTY_PRINT));
		mkdir("databases/utilisateurs/".$id);
		file_put_contents("databases/utilisateurs/".$id."/voyages.json", json_encode(array()));
		return $id;
	}

	

	function trouverVoyageAvecId($tab_voyage, $id){ # Retourne la ligne avec l'id correspondant dans le tableau (0 si non trouvé). Ca commence à 1
		foreach ($tab_voyage as $key => $value) {
			if($value["id"] == $id){
				return $key+1;
			}
		}
		return 0;
	}

	function recupererVoyageAvecId($id){
		$path = "databases/voyages.json";
		$file_content = file_get_contents($path);
		$content = json_decode($file_content, true);
		$indice = trouverVoyageAvecId($content, $id);
		return $content[$indice-1];
	}

	function recupererVoyages(){
		$path = "databases/voyages.json";
		$file_content = file_get_contents($path);
		$content = json_decode($file_content, true);
		return $content;
	}

	function recupererEtapesVoyage($id){
		$path = "databases/voyages.json";
		$file_content = file_get_contents($path);
		$content = json_decode($file_content, true);
		$indice = trouverVoyageAvecId($content, $id);
		return $content[$indice-1]["etapes"];
	}

	function isAdmin($id){
		$path = "databases/users.json";
		$file_content = file_get_contents($path);
		$content = json_decode($file_content, true);
		$indice = trouverUtilisateurAvecId($content, $id);
		if($content[$indice-1]["role"] == "adm"){
			return true;
		}
		return false;
	}

	function checkUtilisateur($courriel, $mdp, $prenom, $nom, $tel, $naissance, $genre, $role){
		$file_content = file_get_contents("databases/users.json");
		$content = json_decode($file_content, true);
		foreach ($content as $key => $value) {
			if($value["courriel"] == $courriel && estValideMDP($mdp, $value['mdp']) && $value["prenom"] == $prenom && $value["nom"] == $nom && $value["tel"] == $tel && $value["naissance"] == $naissance && $value["genre"] == $genre && $value["role"] == $role){
				return true;
			}
		}
		return false;
	}

	function getId($courriel, $mdp){
		$file_content = file_get_contents("databases/users.json");
		$content = json_decode($file_content, true);
		foreach ($content as $key => $value) {
			if($value["courriel"] == $courriel && estValideMDP($mdp, $value['mdp'])){
				return $value["id"];
			}
		}
		return false;
	}

	function getNom($id){
		$file_content = file_get_contents("databases/users.json");
		$content = json_decode($file_content, true);
		foreach ($content as $key => $value) {
			if($value["id"] == $id){
				return $value["nom"];
			}
		}
		return false;
	}

	function getPrenom($id){
		$file_content = file_get_contents("databases/users.json");
		$content = json_decode($file_content, true);
		foreach ($content as $key => $value) {
			if($value["id"] == $id){
				return $value["prenom"];
			}
		}
		return false;
	}

	function getTel($id){
		$file_content = file_get_contents("databases/users.json");
		$content = json_decode($file_content, true);
		foreach ($content as $key => $value) {
			if($value["id"] == $id){
				return $value["tel"];
			}
		}
		return false;
	}

	function getRole($id){
		$file_content = file_get_contents("databases/users.json");
		$content = json_decode($file_content, true);
		foreach ($content as $key => $value) {
			if($value["id"] == $id){
				return $value["role"];
			}
		}
		return false;
	}

	function getGenre($id){
		$file_content = file_get_contents("databases/users.json");
		$content = json_decode($file_content, true);
		foreach ($content as $key => $value) {
			if($value["id"] == $id){
				return $value["genre"];
			}
		}
		return false;
	}

	function getNaissance($id){
		$file_content = file_get_contents("databases/users.json");
		$content = json_decode($file_content, true);
		foreach ($content as $key => $value) {
			if($value["id"] == $id){
				return $value["naissance"];
			}
		}
		return false;
	}

	function avoirListeMotsVoyage($id){
		$voyage = recupererVoyageAvecId($id);
		return json_encode($voyage);
	}

	function avoirDateVoyage($id){
		$voyage = recupererVoyageAvecId($id);
		return $voyage["debut"];
	}
	
	//EXP UTILISATION

	//$tab = readJSONContent("users.json");
	//print($tab[0]["role"]);
	//addJSONContent("users.json", array("nom"=>"Manchec","prenom"=>"Sergueï", "id"=>"54765", "mdp"=>"a", "role"=>"a", "naissance"=>"12/06/2006", "genre"=>"M", "tel"=>"0767239623", "courriel"=>"serguei.manchec@gmail.com"));
?>
