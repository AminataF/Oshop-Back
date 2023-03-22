<?php

namespace App\Controllers;

use App\Models\Brand;
use App\Models\Type;
use App\Models\Category;
use App\Models\Product;

class ProductController extends CoreController
{

    public function productList()
    {
        // on va chercher les données depuis le modèle Product
        // /!\ grâce au mot-clé "static" présent sur la méthode findAll()
        // dans la classe Product, on peut l'appeler directement avec l'opérateur ::
        $products = Product::findAll();

        $this->show('/product/productList', [
            'products' => $products,
        ]);
    }

    public function productAdd()
    {
        $categories = Category::findAll();
        $types = Type::findAll();
        $brands = Brand::findAll();
        $this->show('/product/productAdd', [
            'categories' => $categories,
            'brands' => $brands,
            'types' => $types
        ]);
    }

    public function createProduct()
    {
        $product = new Product();

        // TODO : création de la section ajout d'un produit
        // 1. vérifier la bonne reception de mes données.

        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $picture = $_POST['picture'] ?? '';
        $price = $_POST['price'] ?? '';
        $status = $_POST['status'] ?? '';
        $brand_id = $_POST['brand_id'] ?? '';
        $category_id = $_POST['category_id'] ?? '';
        $type_id = $_POST['type_id'] ?? '';

        // VALIDATION DES DONNÉES
        // @see https://github.com/O-clock-Nazca/S06-E02-atelier-ajout-DB/blob/master/mega_bonus.md

        // on créé un tableau pour y ajouter les erreurs éventuelles
        $errorList = [];

        // le nom doit contenir au moins 3 caractères
        // @see https://www.php.net/manual/fr/function.strlen.php
        if (strlen($name) < 3) {
            // on ajoute ce message au tableau
            $errorList[] = "Le nom doit contenir au moins 3 caractères !";
        }

        // la description ne doit pas être vide
        if (strlen($description) === 0) {
            $errorList[] = "La description doit contenir au moins 5 caractères !";
        }

        // l'image doit commencer par http:// ou https://
        // PHP strpos() retourne false si la chaine n'est pas trouvée
        // @see https://www.php.net/manual/en/function.strpos.php
        if (strpos($picture, 'http://') === false && strpos($picture, 'https://') === false) {
            $errorList[] = "L'URL de l'image doit commencer par http:// ou https:// !";
        }

        // le prix doit être un nombre (entier ou non) supérieur à 0
        if (!is_numeric($price) && (float) $price >= 0) {
            $errorList[] = "Le prix doit être un nombre positif supérieur à zéro.";
        }

        // la note doit être un entier entre 1 et 5 (inclus)
        // on convertit $rate en entier

        if (empty($errorList)) {

            $product->setName($name);
            $product->setDescription($description);
            $product->setPicture($picture);
            $product->setPrice($price);
            $product->setStatus($status);
            $product->setBrandId($brand_id);
            $product->setCategoryId($category_id);
            $product->setTypeId($type_id);

            $success = $product->save();

            if ($success) {
                header('Location: /product/productList');
                exit;
            } else {
               // ça n'a pas fonctionné, on met un message d'erreur.
               $errorList[] = "Erreur lors de la modification.";
            }
            $categories = Category::findAll(); // @todo ORDER BY name ASC;

            // @todo il nous faut les marques

            // @todo il nous faut les types

        }
        // on affiche à nouveau de form d'ajout, mais avec les erreurs & les données erronées
        $this->show('product/add', [
            'categories' => $categories,
        ]);
    }

    public function edit($id)
    {
        $product = Product::find($id);
        $this->show('product/productUpdate', [
            'product' => $product
        ]);
    }

    public function productUpdate($id)
    {

        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $picture = $_POST['picture'] ?? '';
        $status = $_POST['status'] ?? '';
        $price = $_POST['price'] ?? '';
        $brand_id = $_POST['brand_id'] ?? '';
        $type_id = $_POST['type_id'] ?? '';
        $category_id = $_POST['category_id'] ?? '';

        // VALIDATION DES DONNÉES
        // @see https://github.com/O-clock-Nazca/S06-E02-atelier-ajout-DB/blob/master/mega_bonus.md

        // on créé un tableau pour y ajouter les erreurs éventuelles
        $errorList = [];

        // le nom doit contenir au moins 3 caractères
        // @see https://www.php.net/manual/fr/function.strlen.php
        if (strlen($name) < 3) {
            // on ajoute ce message au tableau
            $errorList[] = "Le nom doit contenir au moins 3 caractères !";
        }

        // la description ne doit pas être vide
        if (strlen($description) === 0) {
            $errorList[] = "La description doit contenir au moins 5 caractères !";
        }

        // l'image doit commencer par http:// ou https://
        // PHP strpos() retourne false si la chaine n'est pas trouvée
        // @see https://www.php.net/manual/en/function.strpos.php
        if (strpos($picture, 'http://') === false && strpos($picture, 'https://') === false) {
            $errorList[] = "L'URL de l'image doit commencer par http:// ou https:// !";
        }

        // le prix doit être un nombre (entier ou non) supérieur à 0
        if (!is_numeric($price) && (float) $price >= 0) {
            $errorList[] = "Le prix doit être un nombre positif supérieur à zéro.";
        }


        $product = Product::find($id);

        if (empty($errorList)) {

            $product->setName($name);
            $product->setDescription($description);
            $product->setPicture($picture);
            $product->setPrice($price);
            $product->setStatus($status);
            $product->setBrandId($brand_id);
            $product->setCategoryId($category_id);
            $product->setTypeId($type_id);

            $success = $product->save();

            if ($success) {
                header('Location: /product/productList');
                exit;
            } else {
                // ça n'a pas fonctionné, on met un message d'erreur.
                $errorList[] = "Erreur lors de la modification.";
            }
        }

        $this->show('/product/productUpdate', [
            'product' => $product,
            'errorList' => $errorList,]);
    }

    public function productDelete($id)
    {
        // on récupère la catégorie à supprimer
        $product = Product::find($id);

        // on supprime la catégorie
        $success = $product->delete();

        // si la suppression a fonctionné
        if ($success) {
            // on redirige vers la liste des catégories
            header("Location: /product/productList");
            exit;
        }
    }
}
