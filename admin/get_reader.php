<?php 
/* Cette fonction est declenchee au moyen d'un appel AJAX depuis le formulaire de sortie de livre */
require_once("includes/config.php");
include('includes/function-library.php');

/* On recupere le numero l'identifiant du lecteur SID---*/
$readerSID=$_GET['userId'];
if(!checkStringValidy($readerSID, "/^(SID\d{3,})$/",6)){
	$json = array('status' => -2, NULL);
	echo json_encode($json);
}else{
// On prepare la requete de recherche du lecteur correspondnat
	try{
		$sql = "SELECT FullName FROM tblreaders  WHERE ReaderId = :id";
		$query = $dbh->prepare($sql);
		$query->bindParam(':id', $readerSID);
		// On execute la requete
		$query->execute();
		// On stocke le resultat de recherche dans une variable $result
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		if(!empty($result)){
			$json = [1, $result[0]['FullName']];
		}else{
			$json = [0, NULL];
		}
	}catch (err){
		$json = [-1, NULL];
	}
	echo json_encode($json);
}

// Si un resultat est trouve
	// On affiche le nom du lecteur
	// On active le bouton de soumission du formulaire
// Sinon
	// Si le lecteur n existe pas
		// On affiche que "Le lecteur est non valide"
		// On desactive le bouton de soumission du formulaire
	// Si le lecteur est bloque
		// On affiche lecteur bloque
		// On desactive le bouton de soumission du formulaire


?>