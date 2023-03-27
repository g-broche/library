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
	$updateStatusMsg = "";
	$statusClass = "";
	if (isset($_POST['currentPassword']) && isset($_POST['newPassword']) && isset($_POST['passwordConfirm'])) {
		if (!(checkStringValidy($_POST['currentPassword'], "/^.{6,}$/", 6) && checkStringValidy($_POST['newPassword'], "/^.{6,}$/", 6) &&
			checkStringValidy($_POST['passwordConfirm'], "/^.{6,}$/", 6))) {
			$updateStatusMsg = "le mot de passe comporte moins de 6 charactères";
			$statusClass = "fontRed";
		} else {
			$result = selectAdmin($dbh, $_SESSION['alogin']);
			if ($result[0] == 0) {
				$updateStatusMsg = "incorrect user";
				$statusClass = "fontRed";
			} else if ($result[0] == -1) {
				$updateStatusMsg = "erreur serveur";
				$statusClass = "fontRed";
			} else if (password_verify($_POST['currentPassword'], $result[1]['Password'])) {
				$updateResult = updateAdminPassword($dbh, $result[1]['id'], password_hash($_POST['newPassword'], PASSWORD_DEFAULT));
				if ($updateResult) {
					$updateStatusMsg = "le changement a bien été enregistré";
					$statusClass = "fontGreen";
				} else {
					$updateStatusMsg = "erreur serveur";
					$statusClass = "fontRed";
				}
			} else {
				$updateStatusMsg = "mot de pass incorrect";
				$statusClass = "fontRed";
			}
		}
	}
	// Sinon on peut continuer. Après soumission du formulaire de modification du mot de passe
	// Si le formulaire a bien ete soumis
	// On recupere le mot de passe courant
	// On recupere le nouveau mot de passe
	// On recupere le nom de l'utilisateur stocké dans $_SESSION

	// On prepare la requete de recherche pour recuperer l'id de l'administrateur (table admin)
	// dont on connait le nom et le mot de passe actuel
	// On execute la requete

	// Si on trouve un resultat
	// On prepare la requete de mise a jour du nouveau mot de passe de cet id
	// On execute la requete
	// On stocke un message de succès de l'operation
	// On purge le message d'erreur
	// Sinon on a trouve personne	
	// On stocke un message d'erreur

	// Sinon le formulaire n'a pas encore ete soumis
	// On initialise le message de succes et le message d'erreur (chaines vides)
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>Gestion bibliotheque en ligne</title>
	<!-- BOOTSTRAP CORE STYLE  -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
	<!-- FONT AWESOME STYLE  -->
	<link href="assets/css/font-awesome.css" rel="stylesheet" />
	<!-- CUSTOM STYLE  -->
	<link href="assets/css/style.css" rel="stylesheet" />
	<!-- Penser a mettre dans la feuille de style les classes pour afficher le message de succes ou d'erreur  -->
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

	main .wrapper form {
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

	.fontRed {
		color: red;
	}

	.fontGreen {
		color: green;
	}
</style>

<body> <!------MENU SECTION START-->
	<?php include('includes/header.php'); ?>

	<main>
		<div class="container">
			<div class="row">
				<div class="col">
					<h3>CHANGER LE PASS ADMIN</h3>
				</div>
			</div>
			<div>
				<!--  Si on a une erreur, on l'affiche ici -->
				<!--  Si on a un message, on l'affiche ici -->
				<?php
				if ($updateStatusMsg != "") {
					echo "<p class='" . $statusClass . "'>" . $updateStatusMsg . "</p>";
				} ?>
			</div>
			<div class="wrapper">
				<div>
					<div>
						<h5>Changer de mot de passe</h5>
					</div>
					<form method="post" action="change-password.php">
						<div class="form-group">
							<label>Mot de pass actuel</label>
							<input id="currentPassword" type="password" name="currentPassword" required>
						</div>
						<div class="form-group">
							<label>Nouveau mot de pass</label>
							<input id="newPassword" type="password" name="newPassword" required>
						</div>
						<div class="form-group">
							<label>Confirmer le mot de pass</label>
							<input id="passwordConfirm" type="password" name="passwordConfirm" required>
						</div>
						<div><span id="diffPassMsg" class="fontRed"></span></div>
						<button id="submitBTN" type="submit" name="change">Créer</button>
					</form>
				</div>
			</div>
		</div>
	</main>
	<!-- On affiche le titre de la page "Changer de mot de passe"  -->
	<!-- On affiche le message de succes ou d'erreur  -->

	<!-- On affiche le formulaire de changement de mot de passe-->
	<!-- La fonction JS valid() est appelee lors de la soumission du formulaire onSubmit="return valid();" -->

	<!-- CONTENT-WRAPPER SECTION END-->
	<?php include('includes/footer.php'); ?>
	<!-- FOOTER SECTION END-->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
	<script src='js-library.js'></script>
	<script>
		const form = document.querySelector("form");
		const currentPasswordField = document.getElementById("currentPassword");
		const newPasswordField = document.getElementById("newPassword");
		const passwordConfirmField = document.getElementById("passwordConfirm");
		const confirmPassMsg = document.getElementById("diffPassMsg");
		const buttonSubmit = document.getElementById("submitBTN");

		buttonSubmit.disabled = true;
		let isCurrentPassValid = false;
		let isNewPassConfirmed = false;

		form.addEventListener("submit", function(event) {
			event.preventDefault()
		});

		currentPasswordField.addEventListener('input', debounce(100, () => {
			isCurrentPassValid = checkStringValidy(currentPasswordField.value, /^.+$/, 6)
		}, () => {
			enableSubmitButton(buttonSubmit, [isCurrentPassValid, isNewPassConfirmed])
		}));

		newPasswordField.addEventListener('input', debounce(100, () => {
				isNewPassConfirmed = valid(newPasswordField, passwordConfirmField, /^.{6,}$/)
			},
			displayPassNotConfirmed, () => {
				enableSubmitButton(buttonSubmit, [isCurrentPassValid, isNewPassConfirmed])
			}));

		passwordConfirmField.addEventListener('input', debounce(100, () => {
				isNewPassConfirmed = valid(newPasswordField, passwordConfirmField, /^.{6,}$/)
			},
			displayPassNotConfirmed, () => {
				enableSubmitButton(buttonSubmit, [isCurrentPassValid, isNewPassConfirmed])
			}));

		buttonSubmit.addEventListener('click', () => {
			if (isCurrentPassValid && isNewPassConfirmed) {
				form.submit();
			}
		})

		function displayPassNotConfirmed() {
			if (isNewPassConfirmed) {
				confirmPassMsg.textContent = "";
			} else {
				confirmPassMsg.textContent = "le nouveau mot de passe et son champ de confirmation doivent être identiques et faire au moins 6 charactères"
			}
		}
	</script>
</body>

</html>