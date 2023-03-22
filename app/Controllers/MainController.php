<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Product;

// Si j'ai besoin du Model Category
// use App\Models\Category;

class MainController extends CoreController
{
    /**
     * Méthode s'occupant de la page d'accueil
     *
     * @return void
     */
    public function home()
    {
        // pour accéder à cette page, le user doit être connecté et avoir soit le rôle catalog-manager, soit le rôle admin
        $this->checkAuthorization(['catalog-manager', 'admin']);
        // si besoin de tester la 403 (aucun rôle n'accède à cette page) :
        // $this->checkAuthorization([]);
        
        // les 5 dernières catégories
        $fiveCategories = Category::findLastFive();

        // les 5 dernières produits
        $fiveProducts = Product::findLastFive();
        
        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show('main/home',[
            'fiveCategories' => $fiveCategories,
            'fiveProducts' => $fiveProducts,
        ]);
    }
}
