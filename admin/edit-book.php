<?php
session_start();

include('includes/config.php');
include('includes/function-library.php');
include('includes/request-library.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
    // On le redirige vers la page de login  
    header('location:../index.php');


}else if (isset($_POST['bookId'])){
      if( !(isset($_POST['title'], $_POST['categoryId'], $_POST['authorId'], $_POST['ISBN'], $_POST['price']) && ($_POST['bookId']!==null&& $_POST['title']!==null&& $_POST['categoryId']!==null&& $_POST['authorId']!==null&& $_POST['ISBN']!==null&& $_POST['price']!==null) && 
          checkStringValidy($_POST['ISBN'], "/^(\d{10}|\d{13})$/", 10) && checkStringValidy($_POST['price'], "/^\d+(\.\d{2})?$/"))){
            var_dump($_POST);
          echo "<script>alert('le formulaire est incomplet ou comporte des erreurs')</script>";
      }else{
          $successState=updateBook($dbh, $_POST['bookId'], $_POST['title'], $_POST['categoryId'], $_POST['authorId'], $_POST['ISBN'], $_POST['price']);
          header("location:manage-books.php?action=edit&successState=".$successState);
      }
  }else if (isset($_POST['edit']) && $_POST['edit']!==null){
      $categoryList = getCategories($dbh);
      $authorList = getAuthors($dbh);
      $selectedBook = getABookInfos($dbh, $_POST['edit']);

      if($categoryList[0]==-1||$authorList[0]==-1||$selectedBook[0]==-1){
        echo "<script>alert('Erreur serveur')</script>";
        header( "refresh:5; location:manage-books.php" );
      }
      if ($selectedBook[0]==0){
        header("location:manage-books.php?action=edit&successState=0");
      }

}


?>

<!DOCTYPE html>
<html>


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
                    <h3>MODIFIER UN LIVRE</h3>
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
                    <form method="post" action="edit-book.php">
                        <input type="hidden" name="bookId" value=<?=$selectedBook[1]['id'] ?>>
                        <div class="form-group">
                            <label>Titre</label>
                            <input id="title" type="text" name="title" value=<?='"'.$selectedBook[1]['BookName'].'"'?>
                                required>
                        </div>
                        <div class=" form-group">
                            <label>Catégorie</label>
                            <select id="category" name="categoryId" required>
                                <?php foreach($categoryList[1] as $category) :?>
                                <option value="<?=$category['id']?>" <?php 
                                    if ($category['CategoryName']==$selectedBook[1]['CategoryName']){
                                        echo " selected";
                                    }
                                ?>><?=$category['CategoryName']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Auteur</label>
                            <select id="author" name="authorId" required>
                                <?php foreach($authorList[1] as $author) :?>
                                <option value="<?=$author['id']?>" <?php 
                                    if ($author['AuthorName']==$selectedBook[1]['AuthorName']){
                                        echo " selected";
                                    }
                                ?>><?=$author['AuthorName']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>ISBN</label>
                            <input id="isbn" type="text" name="ISBN" value=<?=$selectedBook[1]['ISBNNumber']?> required>
                        </div>
                        <div class="form-group">
                            <label>Prix</label>
                            <input id="price" type="text" name="price" value=<?='"'.$selectedBook[1]['BookPrice'].'"'?>
                                required>
                        </div>

                        <button id="submitBTN" type="submit" name="edit">modifier</button>
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

    function checkAllFields() {
        isTitleValid = titleField.value != "";
        isCategoryValid = isSelectedCategoryValid();
        isAuthorValid = isSelectedAuthorValid();
        isISBNValid = checkStringValidy(ISBNField.value, /^(\d{10}|\d{13})$/, 10);
        isPriceValid = checkStringValidy(priceField.value, /^\d+(\.\d{2})?$/, 1);
    }

    function createListTextFromSelect(selectElement) {
        let list = [...selectElement.options].map(o => o.text);
        return list;
    }

    function isSelectedCategoryValid() {
        return (categorySelect[categorySelect.selectedIndex].text != "" && createListTextFromSelect(categorySelect)
            .includes(categorySelect[
                categorySelect.selectedIndex].text));
    }

    function isSelectedAuthorValid() {
        return (authorSelect[authorSelect.selectedIndex].text != "" && createListTextFromSelect(authorSelect).includes(
            authorSelect[authorSelect.selectedIndex].text));
    }

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

    categorySelect.addEventListener('input', () => {
        isCategoryValid = isSelectedCategoryValid();
        enableSubmitButton(buttonSubmit, [isTitleValid, isCategoryValid, isAuthorValid, isISBNValid,
            isPriceValid
        ]);
    });

    authorSelect.addEventListener('input', () => {
        isAuthorValid = isSelectedAuthorValid();
        enableSubmitButton(buttonSubmit, [isTitleValid, isCategoryValid, isAuthorValid, isISBNValid,
            isPriceValid
        ]);
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
    checkAllFields();
    </script>
</body>

</html>