<?php

// On récupère la session courante
session_start();

//file_put_contents('readerid.txt', $newID);
// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');
include('includes/function-library.php');
// Après la soumission du formulaire de compte (plus bas dans ce fichier)
// On vérifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
// $_POST["vercode"] et la valeur initialisée $_SESSION["vercode"] lors de l'appel à captcha.php (voir plus bas)
if (isset($_POST["vercode"])) {
    if (!$_POST["vercode"] == $_SESSION["vercode"]) {
        echo "<script>alert('Code de vérification incorrect')</script>";
    } else {

        if (
            areValuesSet($_POST['name'], $_POST['phone'], $_POST['email'], $_POST['password']) &&
            areValuesNotEmpty($_POST['name'], $_POST['phone'], $_POST['email'], $_POST['password'])
        ) {

            try {
                //On lit le contenu du fichier readerid.txt au moyen de la fonction 'file'. Ce fichier contient le dernier identifiant lecteur cree.

                // On incrémente de 1 la valeur lue

                // On ouvre le fichier readerid.txt en écriture

                // On écrit dans ce fichier la nouvelle valeur

                // On referme le fichier

                // On récupère le nom saisi par le lecteur

                $currentUserId = file_get_contents('readerid.txt');
                $currentNumber = strval(intval(substr($currentUserId, 3)) + 1);
                if (strlen($currentNumber) == 1) {
                    $currentNumber = "00" . $currentNumber;
                } else if (strlen($currentNumber) == 2) {
                    $currentNumber = "0" . $currentNumber;
                }
                $newID = "SID" . ($currentNumber);
                file_put_contents('readerid.txt', $newID);


                $newUserName = $_POST['name'];

                // On récupère le numéro de portable
                $newUserPhone = $_POST['phone'];

                // On récupère l'email
                $newUserEmail = $_POST['email'];

                // On récupère le mot de passe
                $newUserPass = password_hash($_POST['password'], PASSWORD_DEFAULT);

                // On fixe le statut du lecteur à 1 par défaut (actif)
                $newUserStatus = 1;

                // On prépare la requete d'insertion en base de données de toutes ces valeurs dans la table tblreaders

                $sqlRequest = "INSERT INTO tblreaders (ReaderId, FullName, EmailId, MobileNumber, Password, Status)
                Values (:id, :name, :email, :phone, :pass, :status)";
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $statement = $dbh->prepare($sqlRequest);
                $statement->bindParam(':id', $newID);
                $statement->bindParam(':name', $newUserName);
                $statement->bindParam(':email', $newUserEmail);
                $statement->bindParam(':phone', $newUserPhone);
                $statement->bindParam(':pass', $newUserPass);
                $statement->bindParam(':status', $newUserStatus);
                // On éxecute la requete
                $statement->execute();
                $dbLastId = lastInsertId($dbh);
                if ($newID == $dbLastId) {
                    echo "<script>alert('dernier ID créé : " . $newID . "');</script>";
                } else {

                    echo 'error : ' . $newID;
                }

                //header('location:index.php');
            } catch (PDOException $e) {
                echo "error on insert :" . $e->getMessage();
            }
        }
    }
}



// On récupère le dernier id inséré en bd (fonction lastInsertId)
function lastInsertId($dbCo)
{
    try {
        $sqlRequest = "SELECT ReaderId FROM tblreaders order by RegDate DESC limit 1";
        $dbCo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $dbCo->prepare("$sqlRequest");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result[0]['ReaderId'];
    } catch (PDOException $e) {
        return false;
    }
}

// Si ce dernier id existe, on affiche dans une pop-up que l'opération s'est bien déroulée, et on affiche l'identifiant lecteur (valeur de $hit[0])

// Sinon on affiche qu'il y a eu un problème
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <title>Gestion de bibliotheque en ligne | Signup</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <!-- link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' / -->
    <script type="text/javascript">
        // On cree une fonction valid() sans paramètre qui renvoie 
        // TRUE si les mots de passe saisis dans le formulaire sont identiques
        // FALSE sinon
        const passField = document.getElementById("password");
        const passFieldConfirm = document.getElementById("passwordConfirm");

        function valid() {
            return (passField.value == passFieldConfirm.value ? true : false);
        }

        // On cree une fonction avec l'email passé en paramêtre et qui vérifie la disponibilité de l'email

        // Cette fonction effectue un appel AJAX vers check_availability.php
        function isEmailTaken(email) {
            const request = fetch()
        }
    </script>
</head>

<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <?php include('includes/header.php'); ?>
    <!--On affiche le titre de la page : CREER UN COMPTE-->
    <div class="container">
        <div class="row">
            <div class="col">
                <h3>CREER UN COMPTE</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
                <form method="post" action="signup.php">
                    <div class="form-group">
                        <label>Entrez votre nom complet</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Portable</label>
                        <input type="text" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label>Mot de passe :</label>
                        <input id="password" type="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label>Confirmez le mot de passe</label>
                        <input id="passwordConfirm" type="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label>Code de vérification</label>
                        <input type="text" name="vercode" required style="height:25px;">&nbsp;&nbsp;&nbsp;<img src="captcha.php">
                    </div>

                    <button type="submit" name="register" class="btn btn-info">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
    <!--On affiche le formulaire de creation de compte-->
    <!--A la suite de la zone de saisie du captcha, on insère l'image créée par captcha.php : <img src="captcha.php">  -->
    <!-- On appelle la fonction valid() dans la balise <form> onSubmit="return valid(); -->
    <!-- On appelle la fonction checkAvailability() dans la balise <input> de l'email onBlur="checkAvailability(this.value)" -->



    <?php include('includes/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>