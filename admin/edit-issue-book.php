<?php
session_start();

include('includes/config.php');
include('includes/function-library.php');
include('includes/request-library.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
    // On le redirige vers la page de login  
    header('location:../index.php');


}else if(isset($_POST['returned'])&&$_POST['returned']!==null){
    $successState=setIssuedReturned($dbh, $_POST['returned']);
    header("location:manage-issued-books.php?action=edit&successState=".$successState);
}else if (isset($_POST['edit']) && $_POST['edit']!==null){
    $sortiInfo = getSpecificIssued($dbh, $_POST['edit']);
    if($sortiInfo[0]==-1){
        echo "<script>alert('Erreur serveur')</script>";
        header( "refresh:5; location:manage-issued-books.php" );
    }
    if ($sortiInfo[0]==0){
        header("location:manage-issued-books.php?action=edit&successState=0");
    }

}

?>
<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Sorties</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<style>
main .wrapper {
    margin-top: 25px;
    padding: 10px 20%;
}

main h5 {
    padding: 0;
    margin: 0;
}

main .wrapper>div {
    border: 1px solid blue;
}

main .wrapper>div>div:first-child {
    padding: 15px 10px;
    background-color: lightblue;
}

.info {
    padding: 15px 10px;
    display: flex;
    flex-direction: column;
    gap: 15px;
}


label {
    font-weight: bold;
}

div.info-group input {
    border-radius: 5px;
}

form button {
    width: fit-content;
    padding: 5px 15px;
    border-radius: 5px;
    border-width: 1px;
}

.action {
    padding: 10px 15px;
    display: flex;
    justify-content: flex-end;
    gap: 20px;
}

button[name="returned"] {
    background-color: lightblue;
}
</style>

<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php');?>

    <main id="addBook">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3>MODIFIER UNE SORTIE</h3>
                </div>
            </div>
            <div class="wrapper">
                <div>
                    <div>
                        <h5>Information sortie</h5>
                    </div>
                    <div class="info">
                        <div> <label>Lecteur :</label><span><?=$sortiInfo[1]['FullName']?></span></div>
                        <div> <label>Titre :</label><span> <?=$sortiInfo[1]['BookName']?></span></div>
                        <div> <label>ISBN :</label><span> <?=$sortiInfo[1]['ISBNNumber']?></span></div>
                        <div> <label>Sorti le :</label><span> <?=$sortiInfo[1]['IssuesDate']?></span></div>
                    </div>
                    <div class=action>
                        <form method="post" action="manage-issued-books.php">
                            <button id="submitBTN" type="submit" name="cancel">retour</button>
                        </form>
                        <?php if ($sortiInfo[1]['ReturnStatus']!=1):?>
                        <form method="post" action="edit-issue-book.php">
                            <button id="submitBTN" type="submit" name="returned"
                                value=<?=$sortiInfo[1]['id']?>>rendu</button>
                        </form>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src='js-library.js'></script>
</body>

</html>