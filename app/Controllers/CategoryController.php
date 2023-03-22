<?php

namespace App\Controllers;

use App\Models\Category;


class CategoryController extends CoreController
{
    public function categoryList()
    {
        // on va chercher les données depuis le modèle Category
        // /!\ grâce au mot-clé "static" présent sur la méthode findAll()
        // dans la classe Category, on peut l'appeler directement avec l'opérateur ::
        $categories = Category::findAll();

        // on génère la vue
        $this->show('category/categoryList', [
            'categories' => $categories,
        ]);
    }


    public function categoryAdd()
    {
        // on créé une catégorie vide
        $category = new Category();

        // pourquoi crée une instance vide lors de la creation d'un type?

        // on lui envoie une catégorie vide, qu'on créé à la volée avec new Category
        // on est obligé de faire ça, parce que dans notre vue on essaye d'accéder à $category->getName(), $category->getSubtitle()
        $this->show('/category/categoryAdd', [
            'category' => $category
        ]);
    }


    public function createCategory()
    {
        // on créé un nouvel objet Category
        $category = new Category();
        // TODO : création de la section ajout d'une catégorie
        // 1. vérifier la bonne reception de mes données.

        $name = $_POST['name'] ?? '';
        $subtitle = $_POST['subtitle'] ?? '';
        $picture = $_POST['picture'] ?? '';


        // on alimente cet objet (on remplit ses propriétés)
        $category->setName($name);
        $category->setSubtitle($subtitle);
        $category->setPicture($picture);
        // on pourrait aussi faire comme ça :
        // if(isset($_POST['picture'])) {
        //     $picture = $_POST['picture'];
        // } else {
        //     $picture = '';
        // }

        // TODO : VALIDATION DES DONNÉES

        // on créé un tableau pour stocker les erreurs éventuelles
        $errorList = [];

        // TODO : remplacer strlen par mb_strlen("utf-8") pour résoudre le bug des accents !
        // voir ici : https://www.askingbox.com/question/php-strlen-wrong-result-for-diacritics-accents-and-unicode-characters

        // mb_strlen est à utiliser à la place de strlen, pour compter correctement les accents !
        if (mb_strlen($name) < 3) {
            $errorList[] = "Le nom doit contenir au moins 3 caractères !";
        }

        // le sous-titre doit contenir au moins 5 caractères
        if (mb_strlen($subtitle, "utf-8") < 5) {
            $errorList[] = "Le sous-titre doit contenir au moins 5 caractères !";
        }

        // substr — Retourne un segment de chaîne
        // l'image doit commencer par http:// ou https://
        if (substr($picture, 0, 7) !== "http://" && substr($picture, 0, 8) !== "https://") {

            $errorList[] = "L'URL de l'image doit commencer par http:// ou https://  !";
        }

        if (empty($errorList)) {
            // si le tableau errorList est vide, ça veut dire qu'il n'y a pas d'erreur
            // donc on peut ajouter à la DB !

            // on dit à cet objet de s'insérer dans la base !
            // insert() renvoit true si l'ajout a fonctionné, false sinon
            $success = $category->save();

            if ($success) {
                // redirection vers la liste des catégories
                header('Location: /category/categoryList');
                // on s'assure de quitter le script PHP
                // ça vient de la doc PHP !
                exit;
            } else {
                // ça n'a pas fonctionné, on met un message d'erreur.
                $errorList[] = "Erreur lors de l'ajout.";
            }
        }
    }

    /**
     * affiche le form de modification
     * (et le pré-rempli avec les données de la catégorie à modifier)
     */
    public function edit($id)
    {
        $category = Category::find($id);

        $this->show('/category/categoryUpdate', [
            'category' => $category
        ]);
    }

    /*
    *Modification de la category
    */
    public function categoryUpdate($id)
    {
        // recupère l'objet Category à modifier
        $category = Category::find($id);

        //1. cet opérateur nous permet de mettre la valeur de $_POST['name'] dans $name s'il est défini, sinon ''
        $name = $_POST['name'] ?? '';
        $subtitle = $_POST['subtitle'] ?? '';
        $picture = $_POST['picture'] ?? '';

        // TODO : VALIDATIONS DES DONNÉES

        // tableau pour stocker les erreurs
        $errorList = [];

        // mb_strlen est à utiliser à la place de strlen, pour compter correctement les accents !
        if (mb_strlen($name) < 3) {
            $errorList[] = "Le nom doit contenir au moins 3 caractères !";
        }

        // le sous-titre doit contenir au moins 5 caractères
        if (mb_strlen($subtitle, "utf-8") < 5) {
            $errorList[] = "Le sous-titre doit contenir au moins 5 caractères !";
        }

        // substr — Retourne un segment de chaîne
        // l'image doit commencer par http:// ou https://
        if (substr($picture, 0, 7) !== "http://" && substr($picture, 0, 8) !== "https://") {

            $errorList[] = "L'URL de l'image doit commencer par http:// ou https://  !";
        }

        if (empty($errorList)) {
            // si le tableau errorList est vide, ça veut dire qu'il n'y a pas d'erreur
            // donc on peut effectuer les modifications en DB !

            // j'alimente l'objet
            $category->setName($name);
            $category->setSubtitle($subtitle);
            $category->setPicture($picture);

            // on dit à cet objet de se mettre à jour dans la base grâce à l'id récupéré!
            // update() renvoit true si la modification a fonctionné, false sinon
            $success = $category->save();

            if ($success) {
                // redirection vers la liste des catégories
                header('Location: /category/categoryList');
                // on s'assure de quitter le script PHP
                // ça vient de la doc PHP !
                exit;
            } else {
                // ça n'a pas fonctionné, on met un message d'erreur.
                $errorList[] = "Erreur lors de la modification.";
            }
        }
        // on affiche à nouveau de form d'ajout, mais avec les erreurs & les données erronées
        $this->show('/category/categoryUpdate');
    }

    public function categoryDelete($id)
    {
        // on récupère la catégorie à supprimer
        $category = Category::find($id);

        // on supprime la catégorie
        $success = $category->delete();

        // si la suppression a fonctionné
        if ($success) {
            // on redirige vers la liste des catégories
            header("Location: /category/categoryList");
            exit;
        }
    }

    public function Home()
    {   // on a besoin des catégories pour dynamiser les listes
        $categories = Category::findAllOrderedByNameAsc();
        // dd($categories);

        $this->show('/category/categoryHome', [
            'categories' => $categories,
        ]);
    }
    public function categoryHome()
    {
        // dd($_POST);
        // ^ array:1 [▼
        //     "emplacement" => array:5 [▼
        //         0 => "17"
        //         1 => "18"
        //         2 => "6"
        //         3 => "7"
        //         4 => "3"
        //     ]
        // ]

        // $_POST['emplacement'] reste un tableau indexé classique, que PHP a créé depuis ce qu'il a reçu de la requête HTTP
        // les indices du tableau $_POST['emplacement'] correspondent à (home_order - 1);
        // les valeurs du tableau correspondent aux id des catégories

        // par ex. avec le dump() ci-dessus, on peut dire que :
        // "la catégorie dont l'id est 18 est à l'emplacement (1 + 1) = 2"// on boucle sur le tableau reçu pour mettre à jour les catégories concernées

        // on doit remettre à zéro tous les home_order
        Category::resetHomeOrder();

        // on boucle sur le tableau reçu pour mettre à jour les catégories concernées
        foreach ($_POST['emplacement'] as $homeOrder => $categoryId) {
            // Active Record => va chercher
            $category = Category::find($categoryId);
            // modifie...
            $category->setHomeOrder($homeOrder + 1);
            // sauvegarde
            $category->save();
        }

        // on redirige vers le formulaire de sélection
        header('Location: /category/categoryHome');
        exit;
    }
}
