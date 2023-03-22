<?php
session_start();

include('includes/config.php');
include('includes/function-library.php');
include('includes/request-library.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
    // On le redirige vers la page de login  
    header('location:../index.php');
}else if (isset($_POST['readerId'])&& isset($_POST['bookISBN'])){
    if ($_POST['readerId']!==null&& $_POST['bookISBN']!==null){
        $successState=insertIssuedBook($dbh, $_POST['readerId'], $_POST['bookISBN']);
        header("location:add-issue-book.php?action=delete&successState=".$successState);
    }else{
        echo "<script>alert('le formulaire est incomplet')</script>";
    }
}else if(isset($_GET['successState'])){
    switch ($_GET['successState']) {
        case -1:
            echo "<script>alert('erreur serveur')</script>";
            break;          
        case 0:
            echo "<script>alert('un ou plusieurs paramètres étaient invalides')</script>";
            break;
        case 1:
            echo "<script>alert('la sortie a bien été enregistrée')</script>";
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

    <title>Gestion de bibliotheque en ligne | Ajout de sortie</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <script>
    // On crée une fonction JS pour récuperer le nom du lecteur à partir de son identifiant
    async function getReaderName(ReaderId) {
        try {
            let response = await fetch("get_reader.php?userId=" + ReaderId)
            let data = await response.json();
            return data;
        } catch (err) {
            return [-2, null];
        }
    }

    // On crée une fonction JS pour recuperer le titre du livre a partir de son identifiant ISBN

    async function getBookTitle(isbn) {
        try {
            let response = await fetch("get_book.php?bookIsbn=" + isbn)
            let data = await response.json();
            console.log(data);
            return data;
        } catch (err) {
            return [-2, null];
        }
    }
    </script>
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

div.form-group input {
    border-radius: 5px;
}

form button {
    width: fit-content;
    padding: 5px 15px;
    border-radius: 5px;
    background-color: lightblue;
    border-width: 1px;
}

label::after {
    content: "*";
    color: red;
}

.fontRed {
    color: red
}
</style>

<body>

    <body>
        <!------MENU SECTION START-->
        <?php include('includes/header.php'); ?>

        <main id="addBook">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h3>SORTIE D'UN LIVRE</h3>
                    </div>
                </div>

                <div class="wrapper">
                    <div>
                        <div>
                            <h5>Sortie d'un livre</h5>
                        </div>
                        <form method="post" action="add-issue-book.php">
                            <div class="form-group">
                                <label>Identifiant lecteur</label>
                                <input id="readerId" type="text" name="readerId"
                                    placeholder="Identifiant lecteur ex: SID001" required>
                                <span id="readerSpan"></span>
                            </div>
                            <div class="form-group">
                                <label>ISBN</label>
                                <input id="bookISBN" type="text" name="bookISBN"
                                    placeholder="Numero ISBN composé de 10 ou 13 chiffres" required>
                                <span id="bookSpan"></span>
                            </div>

                            <button id="submitBTN" type="submit" name="edit">modifier</button>
                        </form>
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
        <script>
        const form = document.querySelector("form");
        const readerField = document.getElementById("readerId");
        const ISBNField = document.getElementById("bookISBN");
        const buttonSubmit = document.getElementById("submitBTN");
        const readerSpan = document.getElementById("readerSpan");
        const bookSpan = document.getElementById("bookSpan");

        buttonSubmit.disabled = true;
        let isReaderValid = false;
        let isIsbnValid = false;

        let readerFetch;
        let bookFetch;


        function alterFormPostFetchReader(readerResult) {
            readerFetch = readerResult;
            checkReaderName();
            enableSubmitButton(buttonSubmit, [isReaderValid, isIsbnValid]);
        }

        function alterFormPostFetchBook(bookResult) {
            bookFetch = bookResult;
            checkBookTitle();
            enableSubmitButton(buttonSubmit, [isReaderValid, isIsbnValid]);
        }

        function displaySIDFormatOnRegexError() {
            readerSpan.classList.add('fontRed');
            readerSpan.textContent = 'merci de respecter le format de type "SID000"';
            isReaderValid = false;
        }

        function displayISBNFormatOnRegexError() {
            bookSpan.classList.add('fontRed');
            bookSpan.textContent = 'un numéro ISBN est une séquence de 10 ou 13 chiffres';
            isIsbnValid = false;
        }

        function checkReaderName() {

            switch (readerFetch[0]) {
                case 1:
                    readerSpan.classList.remove('fontRed');
                    readerSpan.textContent = readerFetch[1];
                    isReaderValid = true;
                    break;
                case 0:
                    readerSpan.classList.add('fontRed');
                    readerSpan.textContent = "aucun utilisateur trouvé pour cet identifiant";
                    isReaderValid = false;
                    break;
                case -1:
                    readerSpan.classList.add('fontRed');
                    readerSpan.textContent = "erreur serveur";
                    isReaderValid = false;
                    break;

                default:
                    readerSpan.classList.add('fontRed');
                    readerSpan.textContent = "erreur indeterminée";
                    isReaderValid = false;
                    break;
            }
        }

        function checkBookTitle() {
            switch (bookFetch[0]) {
                case 1:
                    bookSpan.classList.remove('fontRed');
                    bookSpan.textContent = bookFetch[1];
                    isIsbnValid = true;
                    break;
                case 0:
                    bookSpan.classList.add('fontRed');
                    bookSpan.textContent = "aucun livre trouvé pour ce numéro";
                    isIsbnValid = false;
                    break;
                case -1:
                    bookSpan.classList.add('fontRed');
                    bookSpan.textContent = "erreur serveur";
                    isIsbnValid = false;
                    break;

                default:
                    bookSpan.classList.add('fontRed');
                    bookSpan.textContent = "erreur indeterminée";
                    isIsbnValid = false;
                    break;
            }
        }

        form.addEventListener("submit", function(event) {
            event.preventDefault()
        });

        readerField.addEventListener('blur', () => {
            if (checkStringValidy(readerField.value, /^(SID\d{3,})$/, 6)) {
                getReaderName(readerField.value)
                    .then(
                        (value) => {
                            alterFormPostFetchReader(value);
                        }
                    )
            } else {
                displaySIDFormatOnRegexError();
                enableSubmitButton(buttonSubmit, [isReaderValid, isIsbnValid]);
            }
        });


        ISBNField.addEventListener('blur', () => {
            if (checkStringValidy(ISBNField.value, /^(\d{10}|\d{13})$/, 10, 13)) {
                getBookTitle(ISBNField.value).then((value) => {
                    bookFetch = value;
                    checkBookTitle();
                    enableSubmitButton(buttonSubmit, [isReaderValid, isIsbnValid])
                });
            } else {
                displayISBNFormatOnRegexError();
                enableSubmitButton(buttonSubmit, [isReaderValid, isIsbnValid]);
            }
        });

        buttonSubmit.addEventListener('click', () => {
            if (isReaderValid && isIsbnValid) {
                form.submit();
            }
        })
        </script>
    </body>

</html>