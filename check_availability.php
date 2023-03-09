<?php ?>
<?php

// On inclue le fichier de configuration et de connexion a la base de donnees
require_once("includes/config.php");
// On recupere dans $_GET l email soumis par l'utilisateur
$inputedEmail = $_GET['inputedEmail'];
// On verifie que l'email est un email valide (fonction php filter_var)
if (!filter_var($inputedEmail, FILTER_VALIDATE_EMAIL)) {
	// Si ce n'est pas le cas, on fait un echo qui signale l'erreur
	//echo "<script>alert('email format is invalid')</script>";
	$json = array('status' => -3);
	echo json_encode($json);
} else {


	// Si c'est bon

	// On prepare la requete qui recherche la presence de l'email dans la table tblreaders
	// On execute la requete et on stocke le resultat de recherche
	try {
		$sqlRequest = "SELECT EmailId FROM tblreaders WHERE EmailId=:email";
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$statement = $dbh->prepare("$sqlRequest");
		$statement->bindParam(':email', $inputedEmail);
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		if (!empty($result)) {
			$json = array('status' => 1);
			echo json_encode($json);
		} else {
			$json = array('status' => 0);
			echo json_encode($json);
		}
	} catch (PDOException $e) {
		$json = array('status' => -1);
		echo json_encode($json);
	}
}
// Si le resultat n'est pas vide. On signale a l'utilisateur que cet email existe deja et on desactive le bouton
// de soumission du formulaire

// Sinon on signale a l'utlisateur que l'email est disponible et on active le bouton du formulaire

?>

