<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de donn�es
include('includes/config.php');
include('includes/function-library.php');
include('includes/request-library.php');


if(strlen($_SESSION['login'])==0) {
	// Si l'utilisateur est d�connect�
	// L'utilisateur est renvoy� vers la page de login : index.php
  header('location:index.php');
} else {
	// On r�cup�re l'identifiant du lecteur dans le tableau $_SESSION
	$ReaderId = $_SESSION['rdid'];
	// On veut savoir combien de livres ce lecteur a emprunte
	// On construit la requete permettant de le savoir a partir de la table tblissuedbookdetails
	// On stocke le r�sultat dans une variable

  $returnedValue=getBookLentAmount($dbh, $ReaderId);
  if($returnedValue==-1){
    echo "<script>alert('Couldn't reach database')</script>";
    die();
  }else{
    $bookLentAmount=$returnedValue;
  }

  $returnedValue=getBookNotReturned($dbh, $ReaderId);
  if($returnedValue==-1){
    echo "<script>alert('Couldn't reach database')</script>";
    die();
  }else{
    $bookNotReturned=$returnedValue;
  }

	// On veut savoir combien de livres ce lecteur n'a pas rendu
	// On construit la requete qui permet de compter combien de livres sont associ�s � ce lecteur avec le ReturnStatus � 0 
	
	// On stocke le r�sultat dans une variable

?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Gestion de librairie en ligne | Tableau de bord utilisateur</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!--On inclue ici le menu de navigation includes/header.php-->
    <?php include('includes/header.php');?>
    <!-- On affiche le titre de la page : Tableau de bord utilisateur-->
    <main id="dashboardMain" class=row>
        <div class="bookLentCountWrapper col-2">
            <ul>
                <li>&#9776;</li>
                <li><?php echo $bookLentAmount?></li>
                <li>Livres empruntés</li>
            </ul>
        </div>

        <div class="bookLentCountWrapper col-2">
            <ul>
                <li>&#9851;</li>
                <li><?php echo $bookNotReturned?></li>
                <li>Livres non encore rendus</li>
            </ul>
        </div>
    </main>

    <!-- On affiche la carte des livres emprunt�s par le lecteur-->

    <!-- On affiche la carte des livres non rendus le lecteur-->

    <?php include('includes/footer.php');?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
<?php  } ?>