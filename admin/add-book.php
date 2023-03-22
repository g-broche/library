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

    $categoryList=getCategories($dbh);
    $authorList=getAuthors($dbh);

    if(isset($_GET['successState'])){
        switch ($_GET['successState']) {
            case -1:
                echo "<script>alert('erreur serveur')</script>";
                break;
            
            case 0:
                echo "<script>alert('un livre avec cet ISBN existe déjà')</script>";
                break;
            
            case 1:
                echo "<script>alert('le livre a été ajouté')</script>";
                break;
            
            default:
            echo "<script>alert('erreur indéterminée')</script>";
                break;
        }
    }

// Sinon on peut continuer. Après soumission du formulaire de creation
    if (isset($_POST['title'])){
        if( !(isset($_POST['title']) && isset($_POST['categoryName']) && isset($_POST['authorName']) && isset($_POST['ISBN']) && isset($_POST['price']) &&
         ($_POST['title']!==null&& $_POST['categoryName']!==null&& $_POST['authorName']!==null&& $_POST['ISBN']!==null&& $_POST['price']!==null) && 
            checkStringValidy($_POST['ISBN'], "/^(\d{10}|\d{13})$/", 10) && checkStringValidy($_POST['price'], "/^\d+(\.\d{2})?$/"))){
            echo "<script>alert('le formulaire est incomplet ou comporte des erreurs')</script>";
        }else{
            $successState=addNewBook($dbh, $_POST['title'], $_POST['categoryName'], $_POST['authorName'], $_POST['ISBN'], $_POST['price']);
            header("location:add-book.php?successState=".$successState);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Ajout de livres</title>
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
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>

    <main id="addBook">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3>AJOUTER UN LIVRE</h3>
                </div>
            </div>
            <?php 
                if($categoryList[0]==0||$authorList[0]==0){
                    if($categoryList[0]==0){
                        echo "<p class='fontRed'>Veulliez préalablement créer au moins une catégorie</p>";
                    }
                    if($authorList[0]==0){
                        echo "<p class='fontRed'>Veulliez préalablement ajouter au moins un auteur</p>";
                    }
                }else{

            ?>
            <div class="wrapper">
                <div>
                    <div>
                        <h5>Information livre</h5>
                    </div>
                    <form method="post" action="add-book.php">
                        <div class="form-group">
                            <label>Titre</label>
                            <input id="title" type="text" name="title" required>
                        </div>
                        <div class="form-group">
                            <label>Catégorie</label>
                            <select id="category" name="categoryName" required>
                                <?php foreach($categoryList[1] as $category) :?>
                                <option value="<?=$category['id']?>"><?=$category['CategoryName']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Auteur</label>
                            <select id="author" name="authorName" required>
                                <?php foreach($authorList[1] as $author) :?>
                                <option value="<?=$author['id']?>"><?=$author['AuthorName']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>ISBN</label>
                            <input id="isbn" type="text" name="ISBN" required>
                        </div>
                        <div class="form-group">
                            <label>Prix</label>
                            <input id="price" type="text" name="price" required>
                        </div>

                        <button id="submitBTN" type="submit" name="add">Ajouter</button>
                    </form>
                </div>
            </div>
            <?php } ?>
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
    const titleField = document.getElementById("title");
    const categorySelect = document.getElementById("category");
    const authorSelect = document.getElementById("author");
    const ISBNField = document.getElementById("isbn");
    const priceField = document.getElementById("price");
    const buttonSubmit = document.getElementById("submitBTN");

    buttonSubmit.disabled = true;
    let isTitleValid = false;
    let isCategoryValid = false;
    let isAuthorValid = false;
    let isISBNValid = false;
    let isPriceValid = false;

    form.addEventListener("submit", function(event) {
        event.preventDefault()
    });

    titleField.addEventListener('input', debounce(100, () => {
        isTitleValid = (titleField.value != "")
    }, () => {
        enableSubmitButton(buttonSubmit, [isTitleValid, isCategoryValid, isAuthorValid, isISBNValid,
            isPriceValid
        ])
    }));

    categorySelect.addEventListener('change', () => {
        isCategoryValid = (categorySelect.value != "")
    }, () => {
        enableSubmitButton(buttonSubmit, [isTitleValid, isCategoryValid, isAuthorValid, isISBNValid,
            isPriceValid
        ])
    });

    authorSelect.addEventListener('change', () => {
        isAuthorValid = (authorSelect.value != "")
    }, () => {
        enableSubmitButton(buttonSubmit, [isTitleValid, isCategoryValid, isAuthorValid, isISBNValid,
            isPriceValid
        ])
    });

    ISBNField.addEventListener('input', debounce(100, () => {
        isISBNValid = checkStringValidy(ISBNField.value, /^(\d{10}|\d{13})$/, 10)
    }, () => {
        enableSubmitButton(buttonSubmit, [isTitleValid, isCategoryValid, isAuthorValid, isISBNValid,
            isPriceValid
        ])
    }));

    priceField.addEventListener('input', debounce(100, () => {
        isPriceValid = checkStringValidy(priceField.value, /^\d+(\.\d{2})?$/, 1)
    }, () => {
        enableSubmitButton(buttonSubmit, [isTitleValid, isCategoryValid, isAuthorValid, isISBNValid,
            isPriceValid
        ])
    }));

    buttonSubmit.addEventListener('click', () => {
        if (isTitleValid && isCategoryValid && isAuthorValid && isISBNValid && isPriceValid) {
            form.submit();
        }
    })

    isCategoryValid = (categorySelect.value != "");
    isAuthorValid = (authorSelect.value != "");
    </script>
</body>

</html>