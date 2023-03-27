<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion � la base de donn�es
include('includes/config.php');
include('includes/function-library.php');
include('includes/request-library.php');

// Si l'utilisateur n'est pas logue, on le redirige vers la page de login (index.php)
$updateStatusMsg = "";
$statusClass = "";
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else if (isset($_POST['newPassword'])) {
    if (
        isset($_POST['currentPassword']) &&
        ($_POST['currentPassword'] !== null && $_POST['newPassword'] !== null)
    ) {
        if (!(checkStringValidy($_POST['newPassword'], "/^.+$/", 6) && checkStringValidy($_POST['currentPassword'], "/^.+$/", 6))) {
            $updateStatusMsg = "le mot de passe comporte moins de 6 charactères";
            $statusClass = "fontRed";
        } else {
            $result = selectUser($dbh, $_SESSION['login']);
            if (empty($result)) {
                $updateStatusMsg = "mot de pass incorrect";
                $statusClass = "fontRed";
            } else if (password_verify($_POST['currentPassword'], $result['Password'])) {
                $updateResult = updateUserPassword($dbh, $result['id'], password_hash($_POST['newPassword'], PASSWORD_DEFAULT));
                if ($updateResult) {
                    $updateStatusMsg = "le changement a bien été enregistré";
                    $statusClass = "fontGreen";
                } else {
                    $updateStatusMsg = "erreur serveur";
                    $statusClass = "fontRed";
                }
            } else {
                $updateStatusMsg = "mot de pass incorrect";
                $statusClass = "fontRed";
            }
        }
    }
}
// sinon, on peut continuer,
// si le formulaire a ete envoye : $_POST['change'] existe
// On recupere le mot de passe et on le crypte (fonction php password_hash)
// On recupere l'email de l'utilisateur dans le tabeau $_SESSION
// On cherche en base l'utilisateur avec ce mot de passe et cet email
// Si le resultat de recherche n'est pas vide
// On met a jour en base le nouveau mot de passe (tblreader) pour ce lecteur
// On stocke le message d'operation reussie
// sinon (resultat de recherche vide)
// On stocke le message "mot de passe invalide"
?>



<style>
    .fontGreen {
        color: green;
    }

    .fontRed {
        color: red;
    }
</style>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | changement de mot de passe</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />

    <!-- Penser au code CSS de mise en forme des message de succes ou d'erreur -->

</head>

<body>
    <!-- Mettre ici le code CSS de mise en forme des message de succes ou d'erreur -->
    <?php include('includes/header.php'); ?>

    <main>
        <!--On affiche le titre de la page : CHANGER MON MOT DE PASSE-->
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3>RECUPERATION DE MOT DE PASSE</h3>
                </div>
            </div>
            <div>
                <!--  Si on a une erreur, on l'affiche ici -->
                <!--  Si on a un message, on l'affiche ici -->
                <?php
                if ($updateStatusMsg != "") {
                    echo "<p class='" . $statusClass . "'>" . $updateStatusMsg . "</p>";
                } ?>
            </div>
            <!--On insere le formulaire de recuperation-->
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
                    <form method="post" action="change-password.php">

                        <div class="form-group">
                            <label>mot de pass actuel:</label>
                            <input id="currentPassword" type="password" name="currentPassword" required>
                        </div>

                        <div class="form-group">
                            <label>Nouveau mot de passe :</label>
                            <input id="newPassword" type="password" name="newPassword" required>
                        </div>

                        <div class="form-group">
                            <label>Confirmez le mot de passe</label>
                            <input id="passwordConfirm" type="password" required>
                        </div>

                        <div class="form-group">
                            <label>Code de vérification</label>
                            <input type="text" name="vercode" required style="height:25px;" required>&nbsp;&nbsp;&nbsp;<img src="captcha.php">
                        </div>

                        <button id="submitBTN" type="submit" name="change" class="btn btn-info">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <!--On affiche le formulaire-->
    <!-- la fonction de validation de mot de passe est appelee dans la balise form : onSubmit="return valid();" (OU PAS)-->


    <?php include('includes/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src='js-library.js'></script>
    <script>
        const form = document.querySelector("form");
        const currentPasswordField = document.getElementById("currentPassword");
        const newPasswordField = document.getElementById("newPassword");
        const passwordConfirmField = document.getElementById("passwordConfirm");
        const buttonSubmit = document.getElementById("submitBTN");

        buttonSubmit.disabled = true;
        let isCurrentPassValid = false;
        let isNewPassConfirmed = false;

        form.addEventListener("submit", function(event) {
            event.preventDefault()
        });

        currentPasswordField.addEventListener('input', debounce(100, () => {
            isCurrentPassValid = checkStringValidy(currentPasswordField.value, /^.+$/, 6)
        }, () => {
            enableSubmitButton(buttonSubmit, [isCurrentPassValid, isNewPassConfirmed])
        }));

        newPasswordField.addEventListener('input', debounce(100, () => {
            isNewPassConfirmed = valid(newPasswordField, passwordConfirmField)
        }, () => {
            enableSubmitButton(buttonSubmit, [isCurrentPassValid, isNewPassConfirmed])
        }));

        passwordConfirmField.addEventListener('input', debounce(100, () => {
            isNewPassConfirmed = valid(newPasswordField, passwordConfirmField)
        }, () => {
            enableSubmitButton(buttonSubmit, [isCurrentPassValid, isNewPassConfirmed])
        }));

        buttonSubmit.addEventListener('click', () => {
            if (isCurrentPassValid && isNewPassConfirmed) {
                form.submit();
            }
        })
    </script>
</body>

</html>