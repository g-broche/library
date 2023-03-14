<?php


// On demarre ou on recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion � la base de donn�es
include('includes/config.php');
include('includes/function-library.php');
include('includes/request-library.php');

// On invalide le cache de session $_SESSION['alogin'] = ''
if (isset($_SESSION['login']) && $_SESSION['alogin'] != '') {
    $_SESSION['alogin'] = '';
}

// A faire :
// Apres la soumission du formulaire de login (plus bas dans ce fichier)
if (isset($_POST['name'])){
    if(!(areValuesSet($_POST['password'],$_POST['vercode'])&&areValuesNotEmpty($_POST['name'], $_POST['password'],$_POST['vercode']))){
    // On verifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
    // $_POST["vercode"] et la valeur initialis�e $_SESSION["vercode"] lors de l'appel a captcha.php (voir plus bas)
        var_dump($_POST);
        echo "<script>alert('il manque des informations')</script>";
        die();
    }else{
        if($_POST['vercode'] != $_SESSION['vercode']){
            echo "<script>alert('le code de vérification est erroné')</script>";
        }else{
            // Le code est correct, on peut continuer
            // On recupere le nom de l'utilisateur saisi dans le formulaire
            // On construit la requete qui permet de retrouver l'utilisateur et son mot de passe a partir de son nom depuis la table admin
            $result = selectAdmin($dbh, $_POST['name']);
            if($result == 0){
                // si aucun utilisateur est trouvé. On le signal par une popup
                echo "<script>alert('Les informations entrées sont erronées')</script>";
                die();
            } else if ($result == -1){
                echo "<script>alert('Erreur serveur')</script>";
                die();
            }else{
                // Si le resultat de recherche n'est pas vide on compare le mot de passe
                if(checkPasswordIsRight($_POST['password'], $result[0]['Password'])){
                // On stocke le nom de l'utilisateur  $_POST['username'] en session $_SESSION
                $_SESSION['alogin']=$result[0]['FullName'];
                echo "<script>alert('Will implement redirect when landing page is done')</script>";
                die();
            }else{
                echo "<script>alert('les informations entrées sont erronées')</script>";
                die();
            }
            }
        }
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <?php include('includes/header.php'); ?>
    <main>
        <div class="container">
            <!--On affiche le titre de la page-->
            <div class="row">
                <div class="col">
                    <h3>LOGIN ADMINISTRATION</h3>
                </div>
            </div>
            <!--On affiche le formulaire de login-->
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
                    <form method="POST" action="adminlogin.php">

                        <div class="form-group">
                            <label>Entrez votre login</label>
                            <input type="text" name="name" required>
                        </div>

                        <div class="form-group">
                            <label>Entrez votre mot de passe</label>
                            <input type="password" name="password" required>
                        </div>
                        <!--A la suite de la zone de saisie du captcha, on ins�re l'image cr��e par captcha.php : <img src="captcha.php">  -->
                        <div class="form-group">
                            <label>Code de vérification</label>
                            <input type="text" name="vercode" required style="height:25px;"
                                required>&nbsp;&nbsp;&nbsp;<img src="captcha.php">
                        </div>
                        <button type="submit" name="login" class="btn btn-info">LOGIN</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <!--MAIN SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src='js-library.js'></script>
</body>

</html>