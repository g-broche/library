<?php
session_start();

include('includes/config.php');
include('includes/function-library.php');
include('includes/request-library.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
     // On le redirige vers la page de login  
     header('location:../index.php');
 }else if (isset($_POST['modify'])){
     if ((isset($_POST['authorName'])&&isset($_POST['authorStatus']))&&($_POST['modify']!==null&&$_POST['authorName']!==null&&$_POST['authorStatus']!==null)&&($_POST['authorStatus']==0||$_POST['authorStatus']==1)){
         $successState=updateAuthor($dbh, $_POST['modify'], $_POST['authorName'], $_POST['authorStatus']);
         $successState=($successState==1? 2:$successState);
         header("location:manage-authors.php?status=".$successState);
     } 
 }else if (isset($_POST['edit']) && $_POST['edit']!==null){
     $selectedAuthor= getAuthor($dbh, $_POST['edit']);
 }
?>
<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Auteurs</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<style>
#editAuthor .wrapper {
    margin-top: 25px;
    padding: 10px 20%;
}

#editAuthor h5 {
    padding: 0;
    margin: 0;
}

#editAuthor .wrapper>div {
    border: 1px solid blue;
}

#editAuthor .wrapper>div>div:first-child {
    padding: 15px 10px;
    background-color: lightblue;
}

#editAuthor .wrapper form {
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
    <!-- MENU SECTION END-->
    <!-- On affiche le titre de la page "Editer la categorie-->
    <main id="editAuthor">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3>MODIFIER UN AUTEUR</h3>
                </div>
            </div>
            <div class="wrapper">
                <div>
                    <?php
                        if($selectedAuthor[0]==-1){
                            echo "<script>alert('erreur serveur')</script>";
                        }else if($selectedAuthor[0]==0){
                            echo "<script>alert('aucun auteur correspondant à afficher')</script>";
                        }else if($selectedAuthor[0]==1){
                    ?>
                    <form method="post" action="edit-author.php">
                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" name="authorName" required value="<?=$selectedAuthor[1]['AuthorName']?>">
                        </div>
                        <div class="form-group">
                            <label>Statut</label>
                            <div><input type="radio" name="authorStatus" required value=1
                                    <?php if($selectedAuthor[1]['Status']==1){echo "checked";} ?>><label>Active</label>
                            </div>
                            <div><input type="radio" name="authorStatus" value=0
                                    <?php if($selectedAuthor[1]['Status']==0){echo "checked";} ?>><label>Inactive</label>
                            </div>


                            <button type="submit" name="modify" value='<?=$selectedAuthor[1]['id'] ?>'>Modifier</button>
                    </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>
    <!-- Si la categorie est active (status == 1)-->
    <!-- On coche le bouton radio "actif"-->
    <!-- Sinon-->
    <!-- On coche le bouton radio "inactif"-->

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src='js-library.js'></script>
</body>

</html>