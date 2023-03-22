<?php
session_start();
include('includes/config.php');
include('includes/function-library.php');
include('includes/request-library.php');

if (strlen($_SESSION['alogin']) == 0) {
    // Si l'utilisateur est déconnecté
    // L'utilisateur est renvoyé vers la page de login : index.php
    header('location:../index.php');
}else{
    if (isset($_GET['successState'])){
        switch ($_GET['successState']) {
            case -1:
                echo "<script>alert('erreur serveur')</script>";
                break;
                
            case 0:
                echo "<script>alert('cette sortie n\'existe pas')</script>";
                break;
        
            case 1:
                echo "<script>alert('la sortie a été close')</script>";
                break;
                
            default:
                echo "<script>alert('erreur indéterminée')</script>";
                break;
            }
        }
    $issuedList=getAllIssuedInfos($dbh);
}
?>
<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion des sorties</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<style>
main {
    min-height: 100vh;
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
</style>

<body>
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <main>
        <!-- On affiche le titre de la page-->
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3>Gestion des sorties</h3>
                </div>
            </div>
            <!-- On prevoit ici une div pour l'affichage des erreurs ou du succes de l'operation de mise a jour ou de suppression d'une categorie-->
            <div>
                <?php
                if($issuedList[0]==-1){
                    echo "<span class='fontRed'> Erreur serveur</span>";
                }else if($issuedList[0]==0){
                    echo "<span > Aucune sortie n'est recensée</span>";
                }else if($issuedList[0]==1){
                    $count=1;
                    ?>
                <!-- On affiche le formulaire de gestion des sorties-->
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Lecteur</th>
                            <th>Titre</th>
                            <th>ISBN</th>
                            <th>Sorti le</th>
                            <th>Retourné le</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($issuedList[1] as $newEntry) :?>
                        <tr>
                            <td><?=$count?></td>
                            <td><?=$newEntry['FullName']?></td>
                            <td><?=$newEntry['BookName']?></td>
                            <td><?=$newEntry['ISBNNumber']?></td>
                            <td><?=$newEntry['IssuesDate']?></td>
                            <td>
                                <?php 
                                if($newEntry['ReturnStatus']==1){
                                    echo $newEntry['ReturnDate'];
                                }else{
                                    echo "Non retourné";
                                }
                                ?>
                            </td>

                            <td>
                                <div>
                                    <form action="edit-issue-book.php" method="post">
                                        <button type="submit" name="edit" value=<?=$newEntry['id']?>>Editer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php $count++; ?>
                        <?php endforeach;?>
                    </tbody>
                </table>
                <?php }?>

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