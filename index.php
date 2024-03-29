<?php
// On demarre ou on recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de donnees
include('includes/config.php');
include('includes/function-library.php');
include('includes/request-library.php');

error_log(print_r($_SESSION, 1));

// On invalide le cache de session
if (isset($_SESSION['login']) && $_SESSION['login'] != '') {
    $_SESSION['login'] = '';
}

if (TRUE === isset($_POST['login'])) {
    // Après la soumission du formulaire de login ($_POST['login'] existe - voir pourquoi plus bas)
    // On verifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
    // $_POST["vercode"] et la valeur initialisee $_SESSION["vercode"] lors de l'appel a captcha.php (voir plus bas)
    if ($_POST['vercode'] != $_SESSION['vercode']) {
        // Le code est incorrect on informe l'utilisateur par une fenetre pop_up
        echo "<script>alert('Code de vérification incorrect')</script>";
    } else {
        if (
            (isset($_POST['password']) && isset($_POST['emailid'])) &&
            ($_POST['password'] !== null && $_POST['emailid'] !== null)
        ) {

            $result = getUser($dbh, $_POST['emailid']);
            if (empty($result)) {
                echo "<script>alert('Utilisateur inconnu')</script>";
            } else if (password_verify($_POST['password'], $result->Password)) {
                // On stocke l'identifiant du lecteur (ReaderId) dans $_SESSION['rdid']
                if ($result->Status == 1) {
                    $_SESSION['login'] = $_POST['emailid'];
                    $_SESSION['rdid'] = $result->ReaderId;
                    header('location:dashboard.php');
                } else {
                    // Sinon le compte du lecteur a ete bloque. On informe l'utilisateur par un popu
                    echo "<script>alert('Votre compte à été bloqué')</script>";
                }
            } else {
                echo "<script>alert('Utilisateur inconnu')</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="FR">

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
    <!--On inclue ici le menu de navigation includes/header.php-->
    <?php include('includes/header.php'); ?>

    <!-- On insere le titre de la page (LOGIN UTILISATEUR) -->
    <main>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3>LOGIN LECTEUR</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
                    <form method="post" action="index.php">
                        <div class="form-group">
                            <label>Entrez votre email</label>
                            <input type="text" name="emailid" required>
                        </div>

                        <div class="form-group">
                            <label>Entrez votre mot de passe</label>
                            <input type="password" name="password" required>
                            <p>
                                <a href="user-forgot-password.php">Mot de passe oublié ?</a>
                            </p>
                        </div>

                        <div class="form-group">
                            <label>Code de vérification</label>
                            <input type="text" name="vercode" required style="height:25px;" required>&nbsp;&nbsp;&nbsp;<img src="captcha.php">
                        </div>

                        <button type="submit" name="login" class="btn btn-info">LOGIN</button>&nbsp;&nbsp;&nbsp;<a href="signup.php">Je n'ai pas de compte</a>
                    </form>
                </div>
            </div>
        </div>
    </main>


    <!--On insere le formulaire de login-->


    <!--A la suite de la zone de saisie du captcha, on insere l'image cree par captcha.php : <img src="captcha.php">  -->

    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>