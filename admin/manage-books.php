<?php
session_start();
include('includes/config.php');
include('includes/function-library.php');
include('includes/request-library.php');

if (strlen($_SESSION['alogin']) == 0) {
    // Si l'utilisateur est déconnecté
    // L'utilisateur est renvoyé vers la page de login : index.php
    header('location:../index.php');
}else if ((isset($_POST['bookToDelete']) && $_POST['bookToDelete']!==null) && checkStringValidy($_POST['bookToDelete'], "/^(\d+)$/") && $_POST['bookToDelete']>0){
      $successState=deleteBook($dbh, $_POST['bookToDelete']);
      header("location:manage-books.php?action=delete&successState=".$successState);
}else if (isset($_GET['action'])){
    if($_GET['action']=="delete")
    switch ($_GET['successState']) {
        case -1:
            echo "<script>alert('erreur serveur')</script>";
            break;
            
        case 0:
            echo "<script>alert('le livre n\'existe pas')</script>";
            break;
    
        case 1:
            echo "<script>alert('le livre a été supprimé')</script>";
            break;
            
        default:
            echo "<script>alert('erreur indéterminée')</script>";
            break;
    }else if ($_GET['action']=="edit"){
        switch ($_GET['successState']){
        case -1:
            echo "<script>alert('erreur serveur')</script>";
            break;
            
        case 0:
            echo "<script>alert('le livre n\'existe pas')</script>";
            break;
    
        case 1:
            echo "<script>alert('le livre a bien été modifié')</script>";
            break;
            
        default:
            echo "<script>alert('erreur indéterminée')</script>";
            break;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion des livres</title>
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
                    <h3>Gestion des livres</h3>
                </div>
            </div>
            <!-- On prevoit ici une div pour l'affichage des erreurs ou du succes de l'operation de mise a jour ou de suppression d'une categorie-->
            <div>
                <?php
                $tableBooks=getBooksInfos($dbh);
                if($tableBooks[0]==-1){
                    echo "<span class='fontRed'> Erreur serveur</span>";
                }else if($tableBooks[0]==0){
                    echo "<span > Aucun livre n'est recensé</span>";
                }else if($tableBooks[0]==1){
                    $count=1;
                    ?>
                <!-- On affiche le formulaire de gestion des categories-->
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Auteur</th>
                            <th>Numéro ISBN</th>
                            <th>Prix</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($tableBooks[1] as $book) :?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$book['BookName']?></td>
                            <td><?=$book['CategoryName']?></td>
                            <td><?=$book['AuthorName']?></td>
                            <td><?=$book['ISBNNumber']?></td>
                            <td><?=$book['BookPrice']?></td>
                            <td>
                                <div>
                                    <form action="edit-book.php" method="post">
                                        <button type="submit" name="edit" value=<?=$book['id']?>>Editer</button>
                                    </form>
                                    <form class="deleteForm" action="manage-books.php" method="post">
                                        <button class="deleteBTN" type="submit" name="delete"
                                            value=<?=$book['id']?>>Supprimer</button>
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
    <script>
    const delFormArray = document.getElementsByClassName("deleteForm");
    const delButtonArray = document.getElementsByClassName("deleteBTN");

    for (let i = 0; i < delFormArray.length; i++) {
        delFormArray[i].addEventListener("submit", function(event) {
            event.preventDefault();
        });
    }

    for (let i = 0; i < delButtonArray.length; i++) {
        delButtonArray[i].addEventListener("click", (event) => {
            if (confirm("Voulez-vous vraiment supprimer ce livre?")) {
                sendFormWithDeleteId(event);
            }
        });
    }

    function sendFormWithDeleteId(event) {
        let hiddenField = createHiddenField(event.target.value);
        let hiddenForm = createHiddenForm();
        hiddenForm.appendChild(hiddenField);
        document.body.appendChild(hiddenForm);
        console.log(hiddenForm);
        hiddenForm.submit();
    }

    function createHiddenField(id) {
        if (id > 0 && checkStringValidy(id, /^(\d+)$/)) {
            let input = document.createElement("input");
            input.setAttribute("type", "hidden");
            input.setAttribute("name", "bookToDelete");
            input.setAttribute("value", id);
            return input;
        }
    }

    function createHiddenForm() {
        let form = document.createElement("form");
        form.setAttribute("action", "manage-books.php");
        form.setAttribute("method", "post");
        return form;
    }
    </script>
</body>

</html>