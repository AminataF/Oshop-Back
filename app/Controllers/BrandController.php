<?php

namespace App\Controllers;

use App\Models\Brand;
use App\Models\Category;

class BrandController extends CoreController
{
    
    public function brandList()
    {
        // on va chercher les données depuis le modèle type
        // /!\ grâce au mot-clé "static" présent sur la méthode findAll()
        // dans la classe type, on peut l'appeler directement avec l'opérateur ::
        $brands = Brand::findAll();

        // on génère la vue
        $this->show('/brand/brandList', [
            'brands' => $brands,
        ]);
    }
    public function brandAdd()
    {
        $brand = new Brand();
        $this->show('/brand/brandAdd', [
            'brand' => $brand
        ]);
    }

    public function brandCreate(){
        // on créé un nouvel objet brand
        $brand = new Brand();

        $name = $_POST['name'] ?? '';
        // on pourrait aussi faire comme ça :
        // if(isset($_POST['name'])) {
        //     $name = $_POST['name'];
        // } else {
        //     $name = '';

        // on alimente cet objet (on remplit ses propriétés)
        $brand->setName($name);

        // TODO : VALIDATION DES DONNÉES

        // on créé un tableau pour stocker les erreurs éventuelles
        $errorList = [];

        // TODO : remplacer strlen par mb_strlen("utf-8") pour résoudre le bug des accents !
        // voir ici : https://www.askingbox.com/question/php-strlen-wrong-result-for-diacritics-accents-and-unicode-characters

        // mb_strlen est à utiliser à la place de strlen, pour compter correctement les accents !
        if (mb_strlen($name) < 3) {
            $errorList[] = "Le nom doit contenir au moins 3 caractères !";
        }

        if (empty($errorList)) {
            // si le tableau errorList est vide, ça veut dire qu'il n'y a pas d'erreur
            // donc on peut ajouter à la DB !

            // on dit à cet objet de s'insérer dans la base !
            // insert() renvoit true si l'ajout a fonctionné, false sinon
            $success = $brand->save();

            if ($success) {
                // redirection vers la liste des catégories
                header('Location: /brand/brandList');
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
        $brand = Brand::find($id);

        $this->show('/brand/brandUpdate', [
            'brand' => $brand
        ]);
    }

    public function brandUpdate($id)
    {
        // recupère l'objet Category à modifier
        $brand = Brand::find($id);

        //1. cet opérateur nous permet de mettre la valeur de $_POST['name'] dans $name s'il est défini, sinon ''
        $name = $_POST['name'] ?? '';
        // TODO : VALIDATIONS DES DONNÉES

        // tableau pour stocker les erreurs
        $errorList = [];

        // mb_strlen est à utiliser à la place de strlen, pour compter correctement les accents !
        if (mb_strlen($name) < 3) {
            $errorList[] = "Le nom doit contenir au moins 3 caractères !";
        
        }
        $brand->setName($name);

        if (empty($errorList)) {
            // si le tableau errorList est vide, ça veut dire qu'il n'y a pas d'erreur
            // donc on peut effectuer les modifications en DB !

            // j'alimente l'objet


            // on dit à cet objet de se mettre à jour dans la base grâce à l'id récupéré!
            // update() renvoit true si la modification a fonctionné, false sinon
            $success = $brand->save();

            if ($success) {
                // redirection vers la liste des catégories
                header('Location: /brand/brandList');
                // on s'assure de quitter le script PHP
                // ça vient de la doc PHP !
                exit;
            } else {
                // ça n'a pas fonctionné, on met un message d'erreur.
                $errorList[] = "Erreur lors de la modification.";
            }
        }
        // on affiche à nouveau de form d'ajout, mais avec les erreurs & les données erronées
        $this->show('/brand/brandUpdate', [
            'brand' => $brand
        ]);
    }
}