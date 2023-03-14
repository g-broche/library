<?php
// On r�cup�re la session courante
session_start();

// On inclue le fichier de configuration et de connexion � la base de donn�es
include('includes/config.php');
include('includes/function-library.php');
include('includes/request-library.php');


// Si l'utilisateur n'est pas connecte, on le dirige vers la page de login
if(strlen($_SESSION['login'])==0) {
	header('location:index.php');
}else{
    $result=getIssuedBooksHistory($dbh, $_SESSION['rdid']);
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Gestion des livres</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<style>
.fontRed {
    color: red;
}

.fontGreen {
    color: green;
}


table,
td,
th {
    border: 1px solid black;
}

table {
    border-collapse: collapse;
}

th,
td {
    padding: 0px 10px;
    text-align: center
}


td {
    background: lightgrey;
}
</style>

<body>
    <!--On insere ici le menu de navigation T-->
    <?php include('includes/header.php');?>


    <main>
        <!-- On affiche le titre de la page : LIVRES SORTIS -->
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3>LIVRE EMPRUNTES</h3>
                </div>
            </div>

            <div>
                <?php
                if($result[0]==-1){
                    echo "<span class='fontRed'> Erreur serveur</span>";
                }else if($result[0]==-0){
                    echo "<span > Aucun livre d'a été emprunté pour l'heure</span>";
                }else if($result[0]==1){
                    ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Titre</th>
                            <th>ISBN</th>
                            <th>Date de sortie</th>
                            <th>Date de retour</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count=1;
                                foreach($result[1] as $bookIssued){

                                    echo "<tr>
                                    <td>".$count."</td>
                                    <td>".$bookIssued['BookName']."</td>
                                    <td>".$bookIssued['ISBNNumber']."</td>
                                    <td>".$bookIssued['IssuesDate']."</td>";
                                    if($bookIssued['ReturnStatus']==0){
                                        echo "<td class='fontRed'>Non retourné</td>";
                                    }else if($bookIssued['ReturnStatus']==1){
                                        echo "<td class='fontGreen'>".$bookIssued['ReturnDate']."</td>";
                                    }else{
                                        echo "<td class='fontRed'>erreur</td>";
                                    }
                                    $count++;
                                }
                        }?>
                    </tbody>
                </table>

            </div>
        </div>
    </main>
    <!-- On affiche la liste des sorties contenus dans $results sous la forme d'un tableau -->
    <!-- Si il n'y a pas de date de retour, on affiche non retourne -->


    <?php include('includes/footer.php');?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>