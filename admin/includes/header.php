<section class="menu-section">
    <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">Tableau de bord</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Categories
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="add-category.php">Ajouter une catégorie</a></li>
                            <li><a class="dropdown-item" href="manage-categories.php">Gérer les catégories</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Auteurs
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="add-author.php">Ajouter un auteur</a></li>
                            <li><a class="dropdown-item" href="manage-authors.php">Gérer les auteur</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Livres
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="add-book.php">Ajouter un livre</a></li>
                            <li><a class="dropdown-item" href="manage-books.php">Gérer les livres</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Sorties
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="add-issue-book.php">Ajouter une sortie</a></li>
                            <li><a class="dropdown-item" href="manage-issued-books.php">Gérer les sorties</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reg-readers.php">Lecteurs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="change-password.php">Modifier le mot de passe</a>
                    </li>
                </ul>
                <div class="right-div">
                    <a href="logout.php" class="btn btn-danger pull-right">Déconnexion</a>
                </div>
            </div>
        </div>
    </nav>
</section>