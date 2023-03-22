<?php

// POINT D'ENTRÉE UNIQUE :
// FrontController

// inclusion des dépendances via Composer
// autoload.php permet de charger d'un coup toutes les dépendances installées avec composer
// mais aussi d'activer le chargement automatique des classes (convention PSR-4)

use App\Models\Category;

require_once '../vendor/autoload.php';

session_start();




/* ------------
--- ROUTAGE ---
-------------*/


// création de l'objet router
// Cet objet va gérer les routes pour nous, et surtout il va
$router = new AltoRouter();

// le répertoire (après le nom de domaine) dans lequel on travaille est celui-ci
// Mais on pourrait travailler sans sous-répertoire
// Si il y a un sous-répertoire
if (array_key_exists('BASE_URI', $_SERVER)) {
    // Alors on définit le basePath d'AltoRouter
    $router->setBasePath($_SERVER['BASE_URI']);
    // ainsi, nos routes correspondront à l'URL, après la suite de sous-répertoire
} else { // sinon
    // On donne une valeur par défaut à $_SERVER['BASE_URI'] car c'est utilisé dans le CoreController
    $_SERVER['BASE_URI'] = '/';
}

// On doit déclarer toutes les "routes" à AltoRouter,
// afin qu'il puisse nous donner LA "route" correspondante à l'URL courante
// On appelle cela "mapper" les routes
// 1. méthode HTTP : GET ou POST (pour résumer)
// 2. La route : la portion d'URL après le basePath
// 3. Target/Cible : informations contenant
//      - le nom de la méthode à utiliser pour répondre à cette route
//      - le nom du controller contenant la méthode
// 4. Le nom de la route : pour identifier la route, on va suivre une convention
//      - "NomDuController-NomDeLaMéthode"
//      - ainsi pour la route /, méthode "home" du MainController => "main-home"

$router->map('GET','/',['method' => 'home','controller' => '\App\Controllers\MainController' ],'main-home');

// TODO : CATEGORIE HOME


// TODO LES CATEGORIES
// category list
$router->map('GET', '/category/categoryList', ['method' => 'categoryList', 'controller' => '\App\Controllers\CategoryController'], 'category-list');
// category add (affiche le form pour ajouter une catégorie)
$router->map('GET', '/category/categoryAdd', ['method' => 'categoryAdd', 'controller' => '\App\Controllers\CategoryController'], 'category-add');
// category add (traite le form)
// sur route en méthode HTTP POST
$router->map('POST', '/category/categoryAdd', ['method' => 'createCategory', 'controller' => '\App\Controllers\CategoryController'], 'category-create');
//category update (affiche le form)
// sur une methode HTTP GET
$router->map('GET','/category/categoryUpdate/[i:id]',['method' => 'edit','controller' => '\App\Controllers\CategoryController'],'category-edit');
// category update (traite le form des modification)
// sur une methode HTTP POST
$router->map('POST','/category/categoryUpdate/[i:id]',['method' => 'categoryUpdate','controller' => '\App\Controllers\CategoryController'],'category-update');
// product update (traite le form des modification)
// sur une methode HTTP GET
$router->map('GET','/category/categoryDelete/[i:id]',['method' => 'categoryDelete','controller' => '\App\Controllers\CategoryController'],'category-delete');
// pour aller sur la home categorie
// sur une methode HTTP GET
$router->map('GET','/category/categoryHome',['method' => 'Home','controller' => '\App\Controllers\CategoryController'],'category-home');
// pour récuperer et affiche la homeCategory 
// POST
$router->map('POST','/category/categoryHome',['method' => 'categoryHome','controller' => '\App\Controllers\CategoryController'],'category-home-post');


// TODO LES PRODIUTS
// product list
$router->map('GET', '/product/productList', ['method' => 'productList', 'controller' => '\App\Controllers\ProductController'], 'product-list');
// product add
$router->map('GET', '/product/productAdd', ['method' => 'productAdd', 'controller' => '\App\Controllers\ProductController'], 'product-add');
// product add (traite le form)
// sur route en méthode HTTP POST
$router->map('POST', '/product/productAdd', ['method' => 'createProduct', 'controller' => '\App\Controllers\ProductController'], 'product-create');
// product update (traite le form des modification)
// sur une methode HTTP GET
$router->map('GET','/product/productUpdate/[i:id]',['method' => 'edit','controller' => '\App\Controllers\ProductController'],'product-edit');
// product update (traite le form des modification)
// sur une methode HTTP GET
$router->map('POST','/product/productUpdate/[i:id]',['method' => 'productUpdate','controller' => '\App\Controllers\ProductController'],'product-update');
// product update (traite le form des modification)
// sur une methode HTTP GET
$router->map('GET','/product/productDelete/[i:id]',['method' => 'productDelete','controller' => '\App\Controllers\ProductController'],'product-delete');


// TODO LES TYPES 
// affiche la liste des types
// sur route en méthode HTTP GET
$router->map('GET', '/type/typeList', ['method' => 'typeList', 'controller' => '\App\Controllers\TypeController'], 'type-list');
// affiche le formulaire d'ajout de type
// sur route en méthode HTTP GET
$router->map('GET', '/type/typeAdd', ['method' => 'typeAdd', 'controller' => '\App\Controllers\TypeController'], 'type-add');
// traite l'ajout en BDD du type
// sur route en méthode HTTP POST
$router->map('POST', '/type/typeAdd', ['method' => 'typeCreate', 'controller' => '\App\Controllers\TypeController'], 'type-create');
// affiche le formulaire de modification du type
// sur une methode HTTP GET
$router->map('GET','/type/typeUpdate/[i:id]',['method' => 'edit','controller' => '\App\Controllers\TypeController'],'type-edit');
// traite le formulaire de modification du type
// sur une methode HTTP POST
$router->map('POST','/type/typeUpdate/[i:id]',['method' => 'typeUpdate','controller' => '\App\Controllers\TypeController'],'type-update');
// traite la supprésion d'un type
// sur une methode HTTP GET
$router->map('GET','/type/typeDelete/[i:id]',['method' => 'typeDelete','controller' => '\App\Controllers\TypeController'],'type-delete');


// TODO LES MARQUES
// liste des marques
$router->map('GET', '/brand/brandList', ['method' => 'brandList', 'controller' => '\App\Controllers\BrandController'], 'brand-list');
// product add
$router->map('GET', '/brand/brandAdd', ['method' => 'brandAdd', 'controller' => '\App\Controllers\BrandController'], 'brand-add');
// product add (traite le form)
// sur route en méthode HTTP POST
$router->map('POST', '/brand/brandAdd', ['method' => 'brandCreate', 'controller' => '\App\Controllers\BrandController'], 'brand-create');
// affiche le formulaire de modification des marques
// sur une methode HTTP GET
$router->map('GET','/brand/brandUpdate/[i:id]',['method' => 'edit','controller' => '\App\Controllers\BrandController'],'brand-edit');
// product update (traite le form des modification)
// sur une methode HTTP GET
$router->map('POST','/brand/brandUpdate/[i:id]',['method' => 'brandUpdate','controller' => '\App\Controllers\BrandController'],'brand-update');
// product update (traite le form des modification)
// sur une methode HTTP GET
$router->map('GET','/brand/brandDelete/[i:id]',['method' => 'brandDelete','controller' => '\App\Controllers\BrandController'],'brand-delete');


// TODO : LES UTILISATEURS
// route pour aller sur la page de connexion
$router->map('GET', '/user/login', ['method' => 'login', 'controller' => '\App\Controllers\UserController'], 'user-login');
// route qui va faire les vérifications de la connexion
$router->map('POST', '/user/login', ['method' => 'connect', 'controller' => '\App\Controllers\UserController'], 'user-connect');
// route pour la deconnection
$router->map('GET', '/user/logout', ['method' => 'logout', 'controller' => '\App\Controllers\UserController'], 'user-logout');
// user list (affiche les user)
$router->map('GET','/user/userList',['method' => 'userList','controller' => '\App\Controllers\UserController'],'user-list');
// user list(affiche le form)
$router->map('GET','/user/userAdd',['method' => 'userAdd','controller' => '\App\Controllers\UserController'],'user-add');
// user list(ajout des user
$router->map('POST','/user/userAdd',['method' => 'userCreate','controller' => '\App\Controllers\UserController'],'user-create');
/* -------------
--- DISPATCH ---
--------------*/

// On demande à AltoRouter de trouver une route qui correspond à l'URL courante
$match = $router->match();

// Le Dispatcher permet d'effectuer le traitement que l'on faisait précédemment dans notre index.php, à savoir, identifier le contrôleur et la méthode à appeler, et renvoyer vers la page 404 si pas de route trouvée.

// Ensuite, pour dispatcher le code dans la bonne méthode, du bon Controller
// On délègue à une librairie externe : https://packagist.org/packages/benoclock/alto-dispatcher
// 1er argument : la variable $match retournée par AltoRouter
// 2e argument : le "target" (controller & méthode) pour afficher la page 404
$dispatcher = new Dispatcher($match, '\App\Controllers\ErrorController::err404');
// Une fois le "dispatcher" configuré, on lance le dispatch qui va exécuter la méthode du controller
$dispatcher->dispatch();
