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

	function modifierProfileUtilisateur($id, $field, $value) {
		$path = "databases/users.json";
		$content = lireFichierJson($path);
		foreach ($content as &$user) {
			if ($user['id'] == $id) {
				$user[$field] = $value;
				break;
			}
		}
		file_put_contents($path, json_encode($content, JSON_PRETTY_PRINT));
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

	function modifierGenreUtilisateur($id, $nouveauGenre){
		$content = lireFichierJson("databases/users.json");
		$line = trouverUtilisateurAvecId($content, $id);
		if($line != 0){
			$content[$line-1]["genre"] = $nouveauGenre;
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
			$content = array_values($content);
			file_put_contents("databases/users.json",json_encode($content,JSON_PRETTY_PRINT));
			return 0;
		}
	}

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

	function recupererVilles(){
		$voyages = recupererVoyages();
		$villes = [];
		foreach ($voyages as $voyage) {
			$etapes = recupererEtapesVoyage($voyage['id']);
			foreach ($etapes as $etape) {
				if (isset($etape["ville"])) {
					$villes[] = $etape["ville"];
				}
			}
		}
		$villes= array_unique($villes);
		sort($villes);
		return $villes;
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

	function avoirDateVoyage($id){
		$voyage = recupererVoyageAvecId($id);
		return $voyage["debut"];
	}

	function avoirListeMotsVoyage($id){
		$voyage = recupererVoyageAvecId($id);
		return json_encode($voyage);
	}
	
	function recupererPrixVoyage($id){
		$voyage = recupererVoyageAvecId($id);
		return $voyage["tarif"];
	}

	function ajouterVoyageUtilisateur($id, $idVoyage, $date, $transaction, $montant){
		$voyage = array("id"=>$idVoyage, "date"=>$date, "transaction"=>$transaction, "montant"=>$montant);
		$voyages = recupererVoyagesUtilisateur($id);
		$voyages[] = $voyage;
		$path = "databases/utilisateurs/".$id."/voyages.json";
		file_put_contents($path, json_encode($voyages, JSON_PRETTY_PRINT));
	}
	
	function annulerVoyageUtilisateur($id, $idVoyage){
		$voyages = recupererVoyagesUtilisateur($id);
		$indice = trouverVoyageAvecId($voyages, $idVoyage);
		unset($voyages[$indice-1]);
		$content = array_values($voyages);
		$path = "databases/utilisateurs/".$id."/voyages.json";
		file_put_contents($path, json_encode($voyages, JSON_PRETTY_PRINT));
	}

	function recupererTitreVoyage($id){
		$voyage = recupererVoyageAvecId($id);
		return $voyage["nom"];
	}

	function existeDejaTransaction($id, $transaction){
		$voyages = recupererVoyagesUtilisateur($id);
		foreach ($voyages as $key => $value) {
			if($value["transaction"] == $transaction){
				return true;
			}
		}
		return false;
	}

	function existeDejaVoyageUtilisateur($id, $idVoyage){
		$voyages = recupererVoyagesUtilisateur($id);
		foreach ($voyages as $key => $value) {
			if($value["id"] == $idVoyage){
				return true;
			}
		}
		return false;
	}

	function recupererMotsClefVoyage($id){
		$path = "databases/voyages/".$id."/mots_clef.txt";
		$file_content = file_get_contents($path);
		$mots = explode(" ", $file_content);
		return $mots;
	}

	function modifierHebergement($idVoyage, $numEtape, $nouvelleValeur) {
    		$path = "databases/voyages/$idVoyage/options.json";
    		$file_content = file_get_contents($path);
    		$content = json_decode($file_content, true);
    		if (isset($content["etapes"][$numEtape])) {
        		$content["etapes"][$numEtape]["hebergement"] = $nouvelleValeur;
			file_put_contents($path, json_encode($content, JSON_PRETTY_PRINT));
        		return true;
    		}
    		return false; 
	}

	function modifierRestauration($idVoyage, $numEtape, $nouvelleValeur) {
    		$path = "databases/voyages/$idVoyage/options.json";
    		$file_content = file_get_contents($path);
    		$content = json_decode($file_content, true);
    		if (isset($content["etapes"][$numEtape])) {
        		$content["etapes"][$numEtape]["restauration"] = $nouvelleValeur;
			file_put_contents($path, json_encode($content, JSON_PRETTY_PRINT));
        		return true;
    		}
    		return false; 
	}

	function modifierTransports($idVoyage, $numEtape, $nouvelleValeur) {
    		$path = "databases/voyages/$idVoyage/options.json";
    		$file_content = file_get_contents($path);
    		$content = json_decode($file_content, true);
    		if (isset($content["etapes"][$numEtape])) {
        		$content["etapes"][$numEtape]["transports"] = $nouvelleValeur;
			file_put_contents($path, json_encode($content, JSON_PRETTY_PRINT));
        		return true;
    		}
    		return false; 
	}

	function modifierActivite($idVoyage, $numEtape, $nomActivite, $nouvelleValeur) {
		$path = "databases/voyages/$idVoyage/options.json";   
		$file_content = file_get_contents($path);
		$content = json_decode($file_content, true);
		if (isset($content["etapes"][$numEtape])) {
			foreach ($content["etapes"][$numEtape]["activites"] as &$activite) {
				if ($activite["nom"] === $nomActivite) {
					$activite["option"] = $nouvelleValeur;
					file_put_contents($path, json_encode($content, JSON_PRETTY_PRINT));
					return true;
				}
			}
		}
		return false; 
    }

	function recupererOptionsVoyage($id){
		$path = "databases/voyages/$id/options.json";
		$file_content = file_get_contents($path);
		$content = json_decode($file_content, true);
		return $content;
	}

	function recupererLigneVoyageUtilisateur($id, $idVoyage){
		$voyages = recupererVoyagesUtilisateur($id);
		foreach ($voyages as $key => $value) {
			if($value["id"] == $idVoyage){
				return $key;
			}
		}
	}
	function ajouterOptionUtilisateur($id, $idVoyage, $options){
		$path = "databases/utilisateurs/$id/voyages.json";
		$file_content = file_get_contents($path);
		$content = json_decode($file_content, true);
		$content[recupererLigneVoyageUtilisateur($id, $idVoyage)]["options"] = $options;
		file_put_contents($path, json_encode($content, JSON_PRETTY_PRINT));
	}

	function recupererOptionsVoyageUtilisateur($id, $idVoyage){
		$voyages = recupererVoyagesUtilisateur($id);
		foreach ($voyages as $key => $value) {
			if($value["idVoyage"] == $idVoyage){
				return $value["options"];
			}
		}
	}
	
	//EXP UTILISATION

	//$tab = readJSONContent("users.json");
	//print($tab[0]["role"]);
	//addJSONContent("users.json", array("nom"=>"Manchec","prenom"=>"Sergueï", "id"=>"54765", "mdp"=>"a", "role"=>"a", "naissance"=>"12/06/2006", "genre"=>"M", "tel"=>"0767239623", "courriel"=>"serguei.manchec@gmail.com"));
?>
