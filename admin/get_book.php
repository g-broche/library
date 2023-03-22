<?php 
/* Cette fonction est declenchee au moyen d'un appel AJAX depuis le formulaire de sortie de livre */
require_once("includes/config.php");
include('includes/function-library.php');

/* On recupere le numero ISBN du livre*/
$bookISBN=$_GET['bookIsbn'];
if(!checkStringValidy($bookISBN, "/^(\d{10}|\d{13})$/", 10, 13)){
	$json = array('status' => -2, NULL);
	echo json_encode($json);
}else{
// On prepare la requete de recherche du titre correspondant
	try{
		$sql = "SELECT BookName FROM tblbooks  WHERE ISBNNumber = :isbn";
		$query = $dbh->prepare($sql);
		$query->bindParam(':isbn', $bookISBN, PDO::PARAM_INT);
		// On execute la requete
		$query->execute();
		// On stocke le resultat de recherche dans une variable $result
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		if(!empty($result)){
			$json = [1, $result[0]['BookName']];
		}else{
			$json = [0, NULL];
		}
	}catch (err){
		$json = [-1, NULL];
	}
	echo json_encode($json);
}


// On execute la requete
// Si un resultat est trouve
	// On affiche le nom du livre
	// On active le bouton de soumission du formulaire
// Sinon
	// On affiche que "ISBN est non valide"
	// On desactive le bouton de soumission du formulaire 
?>