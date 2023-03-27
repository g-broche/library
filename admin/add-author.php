<?php
session_start();

include('includes/config.php');
include('includes/function-library.php');
include('includes/request-library.php');

if (strlen($_SESSION['alogin']) == 0) {
    // Si l'utilisateur est déconnecté
    // L'utilisateur est renvoyé vers la page de login : index.php
    header('location:../index.php');
} else {
    // Sinon on peut continuer. Après soumission du formulaire de creation

    if (isset($_POST['add'])) {
        if (!(isset($_POST['authorName']) && $_POST['authorName'] !== null)) {
            echo "<script>alert('le formulaire est incomplet')</script>";
        } else {
            $insertResult = addNewAuthor($dbh, $_POST['authorName']);
            echo $insertResult;
            switch ($insertResult) {
                case -1:
                    echo "<script>alert('erreur serveur')</script>";
                    break;

                case 0:
                    echo "<script>alert('l\'auteur existe déjà')</script>";
                    break;

                case 1:
                    echo "<script>alert('l\'auteur a été ajouté')</script>";
                    break;

                default:
                    echo "<script>alert('erreur indéterminée')</script>";
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

    <title>Gestion de bibliothèque en ligne | Ajout d'auteur'</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<style>
    #addCategory .wrapper {
        margin-top: 25px;
        padding: 10px 20%;
    }

    #addCategory h5 {
        padding: 0;
        margin: 0;
    }

    #addCategory .wrapper>div {
        border: 1px solid blue;
    }

    #addCategory .wrapper>div>div:first-child {
        padding: 15px 10px;
        background-color: lightblue;
    }

    #addCategory .wrapper form {
        padding: 15px 10px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .form-group>label {
        font-weight: bold;
    }

    div.form-group:first-child input {
        border-radius: 5px;
    }

    form button {
        width: fit-content;
        padding: 5px 15px;
        border-radius: 5px;
        background-color: lightblue;
        border-width: 1px;
    }
</style>


<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>

    <main id="addCategory">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3>AJOUTER UN AUTEUR</h3>
                </div>
            </div>
            <div class="wrapper">
                <div>
                    <div>
                        <h5>Information auteur</h5>
                    </div>
                    <form method="post" action="add-author.php">
                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" name="authorName" required>
                        </div>
                        <button type="submit" name="add">Créer</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src='js-library.js'></script>
</body>

</html>