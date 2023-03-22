<?php
session_start();

include('includes/config.php');
include('includes/function-library.php');
include('includes/request-library.php');

if (strlen($_SESSION['alogin']) == 0) {
    // Si l'utilisateur est déconnecté
    // L'utilisateur est renvoyé vers la page de login : index.php
    header('location:../index.php');
}else if ((isset($_POST['edit']) && $_POST['edit']!==null)||(isset($_POST['delete']) && $_POST['delete']!==null)){
    if(isset($_POST['delete'])){
        var_dump($_POST['delete']);
        $successState=disableAuthor($dbh, $_POST['delete']);
        header("location:manage-authors.php?status=".$successState);
    }else{
        header("location:manage-authors.php?status=-2");
    }
}else if (isset($_GET['status'])){
    switch ($_GET['status']) {
        case -1:
            echo "<script>alert('erreur serveur')</script>";
            break;
            
        case 0:
            echo "<script>alert('l\'auteur n\'existe pas')</script>";
            break;
    
        case 1:
            echo "<script>alert('l\'auteur a été désactivé')</script>";
            break;

        case 2:
            echo "<script>alert('l\'auteur a bien été modifié')</script>";
            break;
            
        default:
            echo "<script>alert('erreur indéterminée')</script>";
            break;
    }

}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion des auteurs</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<style>
main {
    height: 100vh;
    padding: 20px 30px;
}

.disabled {
    padding: 5px 8px;
    background-color: darkred;
    border-radius: 3px;
    color: white;
}

.enabled {
    padding: 5px 8px;
    background-color: green;
    border-radius: 3px;
    color: white;
}


table,
td,
th {
    border: 1px solid black;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 5px 10px;
}

table tbody tr:nth-child(odd) {
    background-color: silver;
}

table tbody tr td:last-child div {
    display: flex;
    gap: 10px;
}

table button {
    border-radius: 10px;
    padding: 3px 10px;
    color: white;
}

table tbody tr td:last-child div form:first-child button {
    background-color: #2697FA;
}

table tbody tr td:last-child div form:last-child button {
    background-color: #FA2F2F;
}
</style>

<body>
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <main>
        <!-- On affiche le titre de la page-->
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3>Gestion des auteurs</h3>
                </div>
            </div>
            <!-- On prevoit ici une div pour l'affichage des erreurs ou du succes de l'operation de mise a jour ou de suppression d'une categorie-->
            <div>
                <?php
                $tableAuthors=getAuthors($dbh);
                if($tableAuthors[0]==-1){
                    echo "<span class='fontRed'> Erreur serveur</span>";
                }else if($tableAuthors[0]==0){
                    echo "<span > Aucun auteur n'est recensé</span>";
                }else if($tableAuthors[0]==1){
                    $count=1;
                    ?>
                <!-- On affiche le formulaire de gestion des categories-->
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Statut</th>
                            <th>Créée le</th>
                            <th>Mise à jour le</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($tableAuthors[1] as $auteur) :?>

                        <?php 
                        switch ($auteur['Status']) {
                            case 0:
                                $statusClass = 'disabled';
                                $statusMsg = 'inactive';
                                break;
                            case 1:
                                $statusClass = 'enabled';
                                $statusMsg = 'active';
                                break; 
                            default:
                                $statusClass = 'disabled';
                                $statusMsg = 'erreur';
                                break;
                        }?>
                        <tr>
                            <td><?=$count?></td>
                            <td> <span class='<?=$statusClass?>'><?=$statusMsg?></td>
                            <td><?=$auteur['AuthorName']?></td>
                            <td><?=$auteur['creationDate']?></td>
                            <td><?=$auteur['UpdationDate']?></td>
                            <td>
                                <div>
                                    <form action="edit-author.php" method="post"><button type="submit" name="edit"
                                            value=<?=$auteur['id']?>>Editer</button>
                                    </form>
                                    <form action="manage-authors.php" method="post"><button type="submit" name="delete"
                                            value=<?=$auteur['id']?>>Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php $count++; ?>
                        <?php endforeach;?>
                    </tbody>
                </table>
                <?php }?>
                </table>

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