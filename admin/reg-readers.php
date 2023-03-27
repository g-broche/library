<?php
session_start();

include('includes/config.php');
include('includes/function-library.php');
include('includes/request-library.php');

$statusList = [0, 1, 2]; //0=disabled, 1=enabled, 2=blocked

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
  // On le redirige vers la page de login  
  header('location:../index.php');
} else {
  if ((isset($_POST['enable']) && $_POST['enable'] !== null) || (isset($_POST['disable']) && $_POST['disable'] !== null) ||
    (isset($_POST['delete']) && $_POST['delete'] !== null)
  ) {
    $userId = -1;
    $newStatus = -1;
    if (isset($_POST['enable'])) {
      $userId = $_POST['enable'];
      $newStatus = $statusList[1];
    } elseif (isset($_POST['disable'])) {
      $userId = $_POST['disable'];
      $newStatus = $statusList[0];
    } else {
      $userId = $_POST['delete'];
      $newStatus = $statusList[2];
    }
    if ($userId != -1 && in_array($newStatus, $statusList)) {
      updateUserStatus($dbh, $userId, $newStatus);
    }
  }

  $userList = getAllUsers($dbh);
}
// Sinon on affiche la liste des lecteurs de la table tblreaders


// Lors d'un click sur un bouton "inactif", on récupère la valeur de l'identifiant
// du lecteur dans le tableau $_GET['inid']
// et on met à jour le statut (0) dans la table tblreaders pour cet identifiant de lecteur

// Lors d'un click sur un bouton "actif", on récupère la valeur de l'identifiant
// du lecteur dans le tableau $_GET['id']
// et on met à jour le statut (1) dans  table tblreaders pour cet identifiant de lecteur

// Lors d'un click sur un bouton "supprimer", on récupère la valeur de l'identifiant
// du lecteur dans le tableau $_GET['del']
// et on met à jour le statut (2) dans la table tblreaders pour cet identifiant de lecteur

// On récupère tous les lecteurs dans la base de données
?>

<!DOCTYPE html>
<html lang="FR">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <title>Gestion de bibliothèque en ligne | Reg lecteurs</title>
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

  th {
    text-align: center;
  }

  table tbody tr:nth-child(odd) {
    background-color: silver;
  }

  table tbody tr td:last-child div {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 5px;
  }

  table tbody tr td:last-child div form {
    text-align: center;
  }

  table button {
    border-radius: 10px;
    padding: 3px 10px;
    color: white;
  }

  button[name="enable"] {
    background-color: #2697FA;
  }

  button[name="disable"] {
    background-color: orange;
  }

  button[name="delete"] {
    background-color: red;
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
          <h3>Gestion des lecteurs</h3>
        </div>
      </div>
      <!-- On prevoit ici une div pour l'affichage des erreurs ou du succes de l'operation de mise a jour ou de suppression d'une categorie-->
      <div>
        <?php
        if ($userList[0] == -1) {
          echo "<span class='fontRed'> Erreur serveur</span>";
        } elseif ($userList[0] == 0) {
          echo "<span > Aucune sortie n'est recensée</span>";
        } elseif ($userList[0] == 1) {
          $count = 1;
        ?>
          <!-- On affiche le formulaire de gestion des lecteurs-->
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>ID lecteur</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Portable</th>
                <th>Date d'enregistrement</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($userList[1] as $user) : ?>
                <?php
                switch ($user['Status']) {
                  case 0:
                    $statusMsg = "bloqué(e)";
                    break;
                  case 1:
                    $statusMsg = "actif/active";
                    break;
                  case 2:
                    $statusMsg = "supprimé(e)";
                    break;
                  default:
                    $statusMsg = "erreur";
                    break;
                }
                ?>
                <tr>
                  <td><?= $count ?></td>
                  <td><?= $user['ReaderId'] ?></td>
                  <td><?= $user['FullName'] ?></td>
                  <td><?= $user['EmailId'] ?></td>
                  <td><?= $user['MobileNumber'] ?></td>
                  <td><?= $user['RegDate'] ?></td>
                  <td><?= $statusMsg ?></td>
                  <td>
                    <div>
                      <?php if ($user['Status'] == 0) : ?>
                        <form action="reg-readers.php" method="post">
                          <button type="submit" name="enable" value=<?= $user['id'] ?>>activer</button>
                        </form>
                        <form action="reg-readers.php" method="post">
                          <button type="submit" name="delete" value=<?= $user['id'] ?>>Supprimer</button>
                        </form>
                      <?php elseif ($user['Status'] == 1) : ?>
                        <form action="reg-readers.php" method="post">
                          <button type="submit" name="disable" value=<?= $user['id'] ?>>bloquer</button>
                        </form>
                        <form action="reg-readers.php" method="post">
                          <button type="submit" name="delete" value=<?= $user['id'] ?>>Supprimer</button>
                        </form>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
                <?php $count++; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php } ?>

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