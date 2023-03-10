<?php
// On récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');
include('includes/function-library.php');
include('includes/request-library.php');
// Après la soumission du formulaire de login ($_POST['change'] existe
if(isset($_POST['changePassword'])){
     if(!(areValuesSet($_POST['vercode'], $_SESSION["vercode"] ) && areValuesNotEmpty($_POST['vercode'], $_SESSION["vercode"] ) && $_POST['vercode']==$_SESSION["vercode"])){
// On verifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
// $_POST["vercode"] et la valeur initialisee $_SESSION["vercode"] lors de l'appel a captcha.php (voir plus bas)

// Si le code est incorrect on informe l'utilisateur par une fenetre pop_up

          echo "<script>alert('le captcha est incorrect')</script>";
// Sinon on continue
     }else{
        if(!(areValuesSet($_POST['email'],$_POST['phone'],$_POST['changePassword'],$_POST['confirmPassword']) &&
         areValuesNotEmpty($_POST['email'],$_POST['phone'],$_POST['changePassword'],$_POST['confirmPassword']))&&
         checkStringValidy($_POST['phone'],"/^\d{10}$/", 10,10)&& checkStringValidy($_POST['email'],"/^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/", 10, 50)
         &&checkStringValidy($_POST['changePassword'], "/^.+$/", 6)){
            echo "<script>alert('il manque des informations')</script>";
        }else{
            // on recupere l'email et le numero de portable saisi par l'utilisateur
            $id='';
            $email=$_POST['email'];
            $phone=$_POST['phone'];
            $newPass= password_hash($_POST['changePassword'], PASSWORD_DEFAULT);
            // et le nouveau mot de passe que l'on encode (fonction password_hash)
    
            // On cherche en base le lecteur avec cet email et ce numero de tel dans la table tblreaders
            $id=getUserIdForPassRecovery ($dbh, $email, $phone);
    
            if($id == -1){
                echo "<script>alert('Une erreur a eu lieu')</script>";
            }else if($id == 0){
                echo "<script>alert('Informations erronées')</script>";
            }else{
                $updateStatus=updateUserPassword($dbh, $id, $newPass);
                if ($updateStatus){
                    echo "<script>alert('Le changement de mot de passe a été enregistré')</script>";
                }else{
                    echo "<script>alert('Informations erronées')</script>";
                }
            }
        }


    }

}





// On met a jour la table tblreaders avec le nouveau mot de passe
// On informa l'utilisateur par une fenetre popup de la reussite ou de l'echec de l'operation
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Recuperation de mot de passe </title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />

    <script type="text/javascript">
    // On cree une fonction nommee valid() qui verifie que les deux mots de passe saisis par l'utilisateur sont identiques.
    </script>

</head>

<body>
    <!--On inclue ici le menu de navigation includes/header.php-->
    <?php include('includes/header.php'); ?>



    <!-- On insere le titre de la page (RECUPERATION MOT DE PASSE -->
    <div class="container">
        <div class="row">
            <div class="col">
                <h3>RECUPERATION DE MOT DE PASSE</h3>
            </div>
        </div>
        <!--On insere le formulaire de recuperation-->
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
                <form method="post" action="user-forgot-password.php">

                    <div class="form-group">
                        <label>Email</label>
                        <input id="emailField" type="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label>Portable</label>
                        <input id="phone" type="text" name="phone" required>
                    </div>

                    <div class="form-group">
                        <label>Nouveau mot de passe :</label>
                        <input id="password" type="password" name="changePassword" required>
                    </div>

                    <div class="form-group">
                        <label>Confirmez le mot de passe</label>
                        <input id="passwordConfirm" type="password" name="confirmPassword" required>
                    </div>

                    <div class="form-group">
                        <label>Code de vérification</label>
                        <input type="text" name="vercode" required style="height:25px;">&nbsp;&nbsp;&nbsp;<img
                            src="captcha.php">
                    </div>

                    <button id="submitBTN" type="submit" name="register" class="btn btn-info">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
    <!--L'appel de la fonction valid() se fait dans la balise <form> au moyen de la propri�t� onSubmit="return valid();"-->


    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->

</body>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="js-library.js"></script>
<script>
const form = document.querySelector("form");
const phoneField = document.getElementById("phone");
const emailField = document.getElementById("emailField");
const passField = document.getElementById('password');
const passFieldConfirm = document.getElementById('passwordConfirm');
const buttonSubmit = document.getElementById("submitBTN");

buttonSubmit.disabled = true;

let isPhoneValid = false;
let emailIsvalid = false;
let passIsValid = false;

form.addEventListener("submit", function(event) {
    event.preventDefault()
});

phoneField.addEventListener('input', debounce(100, () => {
    isPhoneValid = checkStringValidy(phoneField.value, /^\d{10}$/, 10, 10)
}, () => {
    enableSubmitButton(buttonSubmit, [isPhoneValid, passIsValid, emailIsvalid])
}));

passField.addEventListener('input', debounce(100, () => {
    passIsValid = valid(passField, passFieldConfirm)
}, () => {
    enableSubmitButton(buttonSubmit, [isPhoneValid, passIsValid, emailIsvalid])
}));

passFieldConfirm.addEventListener('input', debounce(100, () => {
    passIsValid = valid(passField, passFieldConfirm)
}, () => {
    enableSubmitButton(buttonSubmit, [isPhoneValid, passIsValid, emailIsvalid])
}));

emailField.addEventListener('input', debounce(100, () => {
    emailIsvalid = checkStringValidy(emailField.value, /^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/, 10, 50)
}, () => {
    enableSubmitButton(buttonSubmit, [isPhoneValid, passIsValid, emailIsvalid])
}));

buttonSubmit.addEventListener('click', () => {
    if (isPhoneValid && passIsValid && emailIsvalid) {
        form.submit();
    }
})
</script>


</html>