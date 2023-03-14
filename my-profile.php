<?php 
// On r�cup�re la session courante
session_start();

// On inclue le fichier de configuration et de connexion � la base de donn�es
include('includes/config.php');
include('includes/function-library.php');
include('includes/request-library.php');

// Si l'utilisateur n'est plus logu�
// On le redirige vers la page de login
if(strlen($_SESSION['login'])==0) {
  header('location:index.php');
}else if(isset($_POST['fullName'])){

    if(areValuesSet($_POST['fullName'],$_POST['phone'], $_POST['email'])&&
    areValuesNotEmpty($_POST['fullName'],$_POST['phone'], $_POST['email'])&&
    checkStringValidy($_POST['phone'],"/^\d{10}$/", 10,10) && checkStringValidy($_POST['email'],"/^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/", 10, 50) &&
    checkStringValidy($_POST['fullName'],"/^[a-zA-Z]+ [a-zA-Z]+$/", 2)){
        $result=updateUser($dbh, $_SESSION['rdid'], $_POST['fullName'], $_POST['phone'], $_POST['email']);
        if($result[0] == 1){
            $_SESSION['login']=$result[1];
            header('location:my-profile.php');
        }else{
            echo "<script>alert('erreur')</script>";
        }
    }
}
	// Sinon on peut continuer. Apr�s soumission du formulaire de profil

    	// On recupere l'id du lecteur (cle secondaire)

        // On recupere le nom complet du lecteur

        // On recupere le numero de portable

		// On update la table tblreaders avec ces valeurs
        // On informe l'utilisateur du resultat de l'operation


	// On souhaite voir la fiche de lecteur courant.
	// On recupere l'id de session dans $_SESSION

	// On prepare la requete permettant d'obtenir 

?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Profil</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<?php 
    $user=selectUser($dbh, $_SESSION['login']);
    if ($user == -1){
        echo "<script>alert('Erreur serveur')</script>";
        die();
    }else{
$userId=$user['ReaderId'];
$userName=$user['FullName'];
$userPhone=$user['MobileNumber'];
$userEmail=$user['EmailId'];
$userCreationDate=$user['RegDate'];
$userLastUpdate=$user['UpdateDate'];
switch ($user['Status']) {
    case '0':
        $userStatus="bloqué";
        $activityClass="fontRed";
        break;
    case '1':
        $userStatus="actif";
        $activityClass="fontGreen";
        break;
    case '2':
        $userStatus="inactif";
        $activityClass="fontOrange";
        break;
    default:
    $userStatus="error";
        $activityClass="fontBlack";
        break;
    }
}?>

<style>
.fontGreen {
    color: green;
}

.fontOrange {
    color: orange;
}

.fontRed {
    color: red;
}

.fontBlack {
    color: black;
}

#profile>div>div:first-child {
    border-bottom: 2px solid gray;
}

#profile form>section {
    width: 50%;
    margin-top: 30px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

#profile form>section input {
    width: 100%;

}

#profile form>section button {
    width: fit-content;
}
</style>


<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <?php include('includes/header.php');?>

    <main id="profile">
        <div class="container">
            <div class="row">
                <div class="col">
                    <!--On affiche le titre de la page : EDITION DU PROFIL-->
                    <h3>MON COMPTE</h3>
                </div>
            </div>
            <div>
                <form method="post" action="my-profile.php">
                    <!--On affiche le formulaire-->
                    <!--On affiche l'identifiant - non editable-->
                    <!--On affiche la date d'enregistrement - non editable-->
                    <!--On affiche la date de derniere mise a jour - non editable-->
                    <!--On affiche la statut du lecteur - non editable-->
                    <!--On affiche le nom complet - editable-->
                    <!--On affiche le numero de portable- editable-->
                    <!--On affiche l'email- editable-->
                    <section>
                        <div><label>Identifiant : </label> <span><?php echo $userId;?></span></div>
                        <div><label>Date d'enregistrement : </label> <span><?php echo $userCreationDate;?></span></div>
                        <div><label>Dernière mise à jour : </label> <span><?php echo $userLastUpdate;?></span></div>
                        <div><label>Status : </label> <span class="fontGreen"><?php echo $userStatus;?></span></div>
                    </section>
                    <section>
                        <div class="form-group">
                            <label>Nom complet :</label>
                            <input id="name" type="text" name="fullName" value=<?php echo "'".$userName."'" ?>required>
                        </div>
                        <div class="form-group">
                            <label>Numéro portable :</label>
                            <input id="phone" type="text" name="phone" value=<?php echo "'".$userPhone."'" ?>required>
                        </div>
                        <div class="form-group">
                            <label>Email :</label>
                            <input type="email" id="email" name="email" value=<?php echo "'".$userEmail."'" ?>required>
                        </div>
                        <button type="submit" id="submitBTN" name="update" class="btn btn-info">Mettre à jour</button>
                    </section>
                </form>
            </div>
        </div>
    </main>


    <?php include('includes/footer.php');?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src='js-library.js'></script>
    <script>
    const form = document.querySelector("form");
    const phoneField = document.getElementById("phone");
    const emailField = document.getElementById("email");
    const nameField = document.getElementById('name');
    const buttonSubmit = document.getElementById("submitBTN");

    buttonSubmit.disabled = true;
    let isNameValid = false;
    let isPhoneValid = false;
    let emailIsvalid = false;

    form.addEventListener("submit", function(event) {
        event.preventDefault()
    });

    phoneField.addEventListener('input', debounce(100, () => {
        isPhoneValid = checkStringValidy(phoneField.value, /^\d{10}$/, 10, 10)
    }, () => {
        enableSubmitButton(buttonSubmit, [isPhoneValid, isNameValid, emailIsvalid])
    }));

    nameField.addEventListener('input', debounce(100, () => {
        isNameValid = checkStringValidy(nameField.value, /^[a-zA-Z]+ [a-zA-Z]+$/, 2)
    }, () => {
        enableSubmitButton(buttonSubmit, [isPhoneValid, isNameValid, emailIsvalid])
    }));

    emailField.addEventListener('input', debounce(100, () => {
        emailIsvalid = checkStringValidy(emailField.value, /^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/, 10, 50)
    }, () => {
        enableSubmitButton(buttonSubmit, [isPhoneValid, isNameValid, emailIsvalid])
    }));

    buttonSubmit.addEventListener('click', () => {
        if (isPhoneValid && isNameValid && emailIsvalid) {
            form.submit();
        }
    })

    isPhoneValid = checkStringValidy(phoneField.value, /^\d{10}$/, 10, 10);
    isNameValid = checkStringValidy(nameField.value, /^[a-zA-Z]+ [a-zA-Z]+$/, 2);
    emailIsvalid = checkStringValidy(emailField.value, /^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/, 10, 50);
    </script>
</body>

</html>