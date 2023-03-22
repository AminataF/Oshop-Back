<?php

namespace App\Controllers;

class CoreController
{
    /**
     * Méthode appelée à chaque instanciation d'un objet
     * Dès qu'on faut un new XxxController();
     */
    public function __construct()
    {
        // les infos reçues d'Altorouter sur l'index
        global $match;

        // dump('contructeur appelé.');

        // <3 clara
        // Notre ACL c'est notre videur, il est à l'entrée de notre code
        // (construct du CoreController) et vérifie les permissions.
        // Ce qui nous évite de devoir le faire dans chaque méthode comme avant.

        // 1 - On récupère le nom de la route courante grâce à $match
        // 2 - On définit la liste des permissions
        // 3 - On vérifie si la route actuelle est dans la liste ACL et si oui, on utilise la méthode chekAutorization en lui envoyant le rôle de la route courante.

        // notre liste de contrôle d'accès (ACL) aurait :
        // en clé => le chemin où ou souhaite se rendre
        // en valeur => la liste des rôles autorisés
        // /!\ on y met que les routes dont on souhaite restreindre l'accès
        // par ex. /user/login ne s'y trouvera pas puisque accessible à tous
        $acl = [
            // les route de la home
            'main-home' =>         ['catalog-manager', 'admin'],
            // les route des catégories
            'category-list' =>     ['catalog-manager', 'admin'],
            'category-add' =>      ['catalog-manager', 'admin'],
            'category-create' =>   ['catalog-manager', 'admin'],
            'category-edit' =>     ['catalog-manager', 'admin'],
            'category-update' =>   ['catalog-manager', 'admin'],
            'category-delete' =>   ['catalog-manager', 'admin'],
            // les route des produits
            'product-list' =>      ['catalog-manager', 'admin'],
            'product-add' =>       ['catalog-manager', 'admin'],
            'product-create' =>    ['catalog-manager', 'admin'],
            'product-edit' =>      ['catalog-manager', 'admin'],
            'product-update' =>    ['catalog-manager', 'admin'],
            // 'product-delete' =>    ['catalog-manager', 'admin'],
            // // routes marques
            // 'brand-list' =>        ['catalog-manager', 'admin'],
            // 'brand-add' =>         ['catalog-manager', 'admin'],
            // 'brand-create' =>      ['catalog-manager', 'admin'],
            // 'brand-edit' =>        ['catalog-manager', 'admin'],
            // 'brand-update' =>      ['catalog-manager', 'admin'],
            // 'brand-delete' =>     ['catalog-manager', 'admin'],
            // les route des utilisateurs
            'user-list' =>         ['admin'],
            'user-add' =>          ['admin'],
            'user-create' =>       ['admin'],
            // ...
        ];

        // la route dont on souhaite vérifier l'autorisation
        // donc la route demandée par la requête HTTP
        // se trouve-t-elle dans la l'ACL ?
        // cette route se trouve dans le $match d'Altorouter
        
        $routeName = $match['name'];
        // dump($routeName); // main-home

        if (array_key_exists($routeName, $acl)) {
            // on doit appeler $this->checkAuthorization()
            // avec la liste des rôles autorisés pour la route donnée
            $authorizedRolesForRoute = $acl[$routeName];
            // dump($authorizedRolesForRoute);
            // pour accéder à cette page, le user doit être connecté et avoir soit le rôle catalog-manager, soit le rôle admin
            $this->checkAuthorization($authorizedRolesForRoute);
        }

        // vérification des tokens CSRF sur *les pages nécessaires*
        $csrfTokenRoutes = [
            // 'user-create',
            'user-connect',
            // 'product-create',
            // 'product-update',
            // 'category-update',
            // 'category-create',
            // 'type-create',
            // 'type-update',
            // 'brand-create',
            // 'brand-update'
        ];

        // si route demandée présente dans la liste, on vérifie le token
        if (in_array($routeName, $csrfTokenRoutes)) {
            $this->checkCsrfToken();
        }
    }

    /**
     * Vérification du token CSRF
     */
    public function checkCsrfToken()
    {
        // on récupère le token du form (en POST) s'il existe
        $postToken = $_POST['csrf_token'] ?? '';

        // on récupère le token en session
        $sessionToken = $_SESSION['csrfToken'] ?? '';

        // si le token reçu esy différent du token en session
        // ou que le token reçu est vide
        if ($postToken != $sessionToken || empty($postToken)) {
            // 403
            http_response_code(403);
            $this->show('error/err403');
            exit;
        }

        // sinon on continue vers la route demandée
        // et on supprime le token en session pour qu'il soit à usage unique
        unset($_SESSION['csrfToken']);
    }


    protected function checkAuthorization($authorizedRoles = [])
    {
        // un user doit être connecté
        if (isset($_SESSION['userObject'])) {
            // on récupère le user dans la session
            $user = $_SESSION['userObject'];
            // on récupère son rôle
            $userRole = $user->getRole(); // ex. admin
            // son rôle fait-il partie des rôles autorisés (reçus en argument)
            // par ex. $roles = ['catalog-manager', 'admin'];
            // @see https://www.php.net/manual/fr/function.in-array.php
            if (in_array($userRole, $authorizedRoles)) {
                // si oui => return true;
                // on sort de la fonction checkAuthorization() et on retourne à "l'appelant"
                return true;
            }
            // si non => 403 Forbidden + un tpl (sans passer par ErrorController)
            // On envoie un status code 403
            http_response_code(403);
            // Puis on gère l'affichage
            $this->show('error/err403');
            // /!\ on stoppe le script PHP / plus rien ne s'exécute derrière
            exit;
        }

        // si non, on le redirige vers le login
        header('Location: /user/login');
        exit;
    }


    /**
     * Méthode permettant d'afficher du code HTML en se basant sur les views
     *
     * @param string $viewName Nom du fichier de vue
     * @param array $viewData Tableau des données à transmettre aux vues
     * @return void
     */
    protected function show(string $viewName, $viewData = [])
    {
        // On globalise $router car on ne sait pas faire mieux pour l'instant
        global $router;

        // Comme $viewData est déclarée comme paramètre de la méthode show()
        // les vues y ont accès
        // ici une valeur dont on a besoin sur TOUTES les vues
        // donc on la définit dans show()
        $viewData['currentPage'] = $viewName;

        // définir l'url absolue pour nos assets
        $viewData['assetsBaseUri'] = $_SERVER['BASE_URI'] . 'assets/';
        // définir l'url absolue pour la racine du site
        // /!\ != racine projet, ici on parle du répertoire public/
        $viewData['baseUri'] = $_SERVER['BASE_URI'];

        // On veut désormais accéder aux données de $viewData, mais sans accéder au tableau
        // La fonction extract permet de créer une variable pour chaque élément du tableau passé en argument
        extract($viewData);
        // => la variable $currentPage existe désormais, et sa valeur est $viewName
        // => la variable $assetsBaseUri existe désormais, et sa valeur est $_SERVER['BASE_URI'] . '/assets/'
        // => la variable $baseUri existe désormais, et sa valeur est $_SERVER['BASE_URI']
        // => il en va de même pour chaque élément du tableau

        // $viewData est disponible dans chaque fichier de vue
        require_once __DIR__ . '/../views/layout/header.tpl.php';
        require_once __DIR__ . '/../views/' . $viewName . '.tpl.php';
        require_once __DIR__ . '/../views/layout/footer.tpl.php';
    }
}
