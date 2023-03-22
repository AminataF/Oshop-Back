<!-- <nav>
    <ul>
        <li><a href="<?= $router->generate('main-home') ?>">Accueil</a></li>
        <li><a href="<?= md5(time()) ?>">404</a></li>
    </ul>
</nav> -->
<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.html">oShop</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <?php if (isset($_SESSION['userObject'])) : ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= $router->generate('main-home') ?>">Accueil <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $router->generate('category-list') ?>">Catégories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $router->generate('product-list') ?>">Produits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $router->generate('type-list') ?>">Types</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $router->generate('brand-list') ?>">Marques</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $router->generate('category-home') ?>">Sélection Accueil</a>
                    </li>
                    <?php if ($_SESSION['userObject']->getRole() === "admin") : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $router->generate('user-list') ?>">Utilisateurs</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-sm btn-danger" href="<?= $router->generate('user-logout') ?>">Déconnexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link">
                            <?php if (isset($_SESSION['userObject'])) : echo $_SESSION['userObject']->getLastname() . " " . $_SESSION['userObject']->getFirstname();
                            endif; ?>
                        </a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $router->generate('user-login') ?>">connexion</a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>