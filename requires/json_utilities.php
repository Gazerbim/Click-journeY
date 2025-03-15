<?php
	function ajouterContenuJson($file, $array){
		$file_content = file_get_contents($file);
		$content = json_decode($file_content, true); 
		array_push($content, $array);
		file_put_contents($file,json_encode($content));
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

	function modifierNomUtilisateur($id, $nouveauNom){
		$content = lireFichierJson("databases/users.json");
		$line = trouverUtilisateurAvecId($content, $id);
		if($line != 0){
			$content[$line-1]["nom"] = $newName;
			file_put_contents("databases/users.json",json_encode($content));
			return 0;
		}
	}

	function modifierPrenomUtilisateur($id, $nouveauPrenom){
		$content = lireFichierJson("databases/users.json");
		$line = trouverUtilisateurAvecId($content, $id);
		if($line != 0){
			$content[$line-1]["prenom"] = $newName;
			file_put_contents("databases/users.json",json_encode($content));
			return 0;
		}
	}

	function modifierCourrielUtilisateur($id, $nouveauCourriel){
		$content = lireFichierJson("databases/users.json");
		$line = trouverUtilisateurAvecId($content, $id);
		if($line != 0){
			$content[$line-1]["courriel"] = $newMail;
			file_put_contents("databases/users.json",json_encode($content));
			return 0;
		}
	}
	
	function modifierRoleUtilisateur($id, $nouveauRole){
		$content = lireFichierJson("databases/users.json");
		$line = trouverUtilisateurAvecId($content, $id);
		if($line != 0){
			$content[$line-1]["role"] = $newRole;
			file_put_contents("databases/users.json",json_encode($content));
			return 0;
		}
	}

	function modifierMotDePasseUtilisateur($id, $nouveauMdp){
		$content = lireFichierJson("databases/users.json");
		$line = trouverUtilisateurAvecId($content, $id);
		if($line != 0){
			$content[$line-1]["mdp"] = $newMdp;
			file_put_contents("databases/users.json",json_encode($content));
			return 0;
		}
	}

	function modifierNaissanceUtilisateur($id, $nouvelleNaissance){
		$content = lireFichierJson("databases/users.json");
		$line = trouverUtilisateurAvecId($content, $id);
		if($line != 0){
			$content[$line-1]["naissance"] = $newNaissance;
			file_put_contents("databases/users.json",json_encode($content));
			return 0;
		}
	}

	function modifierGenreUtilisateur($id, $nouveauGenre){
		$content = lireFichierJson("databases/users.json");
		$line = trouverUtilisateurAvecId($content, $id);
		if($line != 0){
			$content[$line-1]["genre"] = $newGenre;
			file_put_contents("databases/users.json",json_encode($content));
			return 0;
		}
	}

	function modifierTelUtilisateur($id, $nouveauTel){
		$content = lireFichierJson("databases/users.json");
		$line = trouverUtilisateurAvecId($content, $id);
		if($line != 0){
			$content[$line-1]["tel"] = $newTel;
			file_put_contents("databases/users.json",json_encode($content));
			return 0;
		}
	}

	function supprimerUtilisateur($id){
		$content = lireFichierJson("databases/users.json");
		$line = trouverUtilisateurAvecId($content, $id);
		if($line != 0){
			unset($content[$line-1]);
			file_put_contents("databases/users.json",json_encode($content));
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
			if($value["courriel"] == $courriel && $value["mdp"] == $mdp){
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

	function creerVoyage($id, $nom, $description, $tarif, $debut, $fin, $etapes){
		$path = "databases/voyages.json";
		$tab = lireFichierJson($path);
		$tab[] = array("nom"=>$nom, "description"=>$description, "etapes"=>$etapes, "tarif"=>$tarif, "debut"=>$debut, "fin"=>$fin);
		file_put_contents($path, json_encode($tab));
	}

	function ajouterUtilisateur($nom, $prenom, $mdp, $role, $naissance, $genre, $tel, $courriel){
		$id = rand(10000, 99999);
		$path = "databases/users.json";
		$tab = lireFichierJson($path);
		$tab[] = array("nom"=>$nom, "prenom"=>$prenom, "id"=>$id, "mdp"=>$mdp, "role"=>$role, "naissance"=>$naissance, "genre"=>$genre, "tel"=>$tel, "courriel"=>$courriel);
		file_put_contents($path, json_encode($tab));
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


	//EXP UTILISATION

	//$tab = readJSONContent("users.json");
	//print($tab[0]["role"]);
	//addJSONContent("users.json", array("nom"=>"Manchec","prenom"=>"Sergueï", "id"=>"54765", "mdp"=>"a", "role"=>"a", "naissance"=>"12/06/2006", "genre"=>"M", "tel"=>"0767239623", "courriel"=>"serguei.manchec@gmail.com"));
?>
