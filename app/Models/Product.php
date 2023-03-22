<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

/**
 * Une instance de Product = un produit dans la base de données
 * Product hérite de CoreModel
 */
class Product extends CoreModel
{

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $description;
    /**
     * @var string
     */
    private $picture;
    /**
     * @var float
     */
    private $price;
    /**
     * @var int
     */
    private $rate;
    /**
     * @var int
     */
    private $status;
    /**
     * @var int
     */
    private $brand_id;
    /**
     * @var int
     */
    private $category_id;
    /**
     * @var int
     */
    private $type_id;

    /**
     * Méthode permettant de récupérer un enregistrement de la table Product en fonction d'un id donné
     *
     * @param int $productId ID du produit
     * @return Product
     */
    public static  function find($productId)
    {
        // récupérer un objet PDO = connexion à la BDD
        $pdo = Database::getPDO();

        // on écrit la requête SQL pour récupérer le produit
        $sql = '
            SELECT *
            FROM product
            WHERE id = ' . $productId;

        // query ? exec ?
        // On fait de la LECTURE = une récupration => query()
        // si on avait fait une modification, suppression, ou un ajout => exec
        $pdoStatement = $pdo->query($sql);

        // fetchObject() pour récupérer un seul résultat
        // si j'en avais eu plusieurs => fetchAll
        $result = $pdoStatement->fetchObject('App\Models\Product');

        return $result;
    }

    /**
     * Méthode permettant de récupérer tous les enregistrements de la table product
     *
     * @return Product[]
     */
    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `product`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Product');

        return $results;
    }

    public static function findLastFive()
    {

        $pdo = Database::getPDO();
        $sql = 'SELECT product.id, product.name
        FROM `product`
        ORDER BY id DESC
        LIMIT 5';

        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Product');

        return $results;
    }

    public function insert()
    {

        $pdo = Database::getPDO();
        $pdoStatement = $pdo->prepare(
            "
                        INSERT INTO product (name, description, picture, price, status, category_id, type_id, brand_id) 
                        VALUES (:name, :description, :picture, :price, :status, :category_id, :type_id, :brand_id)"
        );
        $pdoStatement->bindParam(':name', $this->name, PDO::PARAM_STR);
        $pdoStatement->bindParam(':description', $this->description, PDO::PARAM_STR);
        $pdoStatement->bindParam(':picture', $this->picture, PDO::PARAM_STR);
        $pdoStatement->bindParam(':price', $this->price, PDO::PARAM_INT);
        $pdoStatement->bindParam(':status', $this->status, PDO::PARAM_INT);
        $pdoStatement->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
        $pdoStatement->bindParam(':type_id', $this->type_id, PDO::PARAM_INT);
        $pdoStatement->bindParam(':brand_id', $this->brand_id, PDO::PARAM_INT);
        $success = $pdoStatement->execute();


        // Si au moins une ligne ajoutée
        // if ($pdoStatement->rowCount() > 0) {
        // Si la requête a fonctionné
        if ($success) {
            // Alors on récupère l'id auto-incrémenté généré par MySQL
            $this->id = $pdo->lastInsertId();

            // On retourne VRAI car l'ajout a parfaitement fonctionné
            return true;
            // => l'interpréteur PHP sort de cette fonction car on a retourné une donnée
        }

        // Si on arrive ici, c'est que quelque chose n'a pas bien fonctionné => FAUX
        return false;
    }


    public function update()
    {
        // $pdo = Database::getPDO();
        // $pdoStatement = $pdo->prepare("
        //                     UPDATE `product` SET name = :name, description = :description, picture = :picture, price = :price,
        //                      status = :status, category_id = :category_id, type_id = :type_id, brand_id = :brand_id
        //                     WHERE id = :id ");
        // // $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Product');
        // $pdoStatement->bindParam(':name', $this->name, PDO::PARAM_STR);
        // $pdoStatement->bindParam(':description', $this->description, PDO::PARAM_STR);
        // $pdoStatement->bindParam(':picture', $this->picture, PDO::PARAM_STR);
        // $pdoStatement->bindParam(':price', $this->price, PDO::PARAM_INT);
        // $pdoStatement->bindParam(':status', $this->status, PDO::PARAM_INT);
        // $pdoStatement->bindParam(':category_id', $this->category_id, PDO::PARAM_INT);
        // $pdoStatement->bindParam(':type_id', $this->type_id, PDO::PARAM_INT);
        // $pdoStatement->bindParam(':brand_id', $this->brand_id, PDO::PARAM_INT);
        // $pdoStatement->bindParam(':id', $id, PDO::PARAM_INT);
        // $success = $pdoStatement->execute();

        // return $success;
         // Récupération de l'objet PDO représentant la connexion à la DB
         $pdo = Database::getPDO();

         // Ecriture de la requête UPDATE
         $sql = "
             UPDATE `product`
             SET
                 name = :name,
                 picture = :picture,
                 description = :description,
                 price = :price,
                 status = :status,
                 brand_id = :brand_id,
                 type_id = :type_id,
                 category_id = :category_id,
                 updated_at = NOW()
             WHERE id = :id
         ";
 
         // on prépare notre requête
         $pdoStatement = $pdo->prepare($sql);
 
         // on bind nos paramètres
         $pdoStatement->bindParam(':name', $this->name);
         $pdoStatement->bindParam(':description', $this->description);
         $pdoStatement->bindParam(':picture', $this->picture);
         $pdoStatement->bindParam(':price', $this->price);
         $pdoStatement->bindParam(':status', $this->status);
         $pdoStatement->bindParam(':brand_id', $this->brand_id);
         $pdoStatement->bindParam(':type_id', $this->type_id);
         $pdoStatement->bindParam(':category_id', $this->category_id);
         // et on oublie pas l'ID !
         $pdoStatement->bindParam(':id', $this->id);
 
         // Execution de la requête préparée avec execute()
         // execute() retourne true ou false
         $success = $pdoStatement->execute();
 
         return $success;
    }

    public function delete()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // on écrit notre requête SQL
        $sql = "DELETE FROM product WHERE id = :id";

        // on prépare cette requête
        $pdoStatement = $pdo->prepare($sql);

        // on bind nos paramètres
        $pdoStatement->bindParam(":id", $this->id);

        // on exécute la requête
        $product = $pdoStatement->execute();

        // on retourne true ou false en fonction de si la requête s'est bien passée ou pas !
        return $product;
    }

    /**
     * Get the value of name
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the value of description
     *
     * @return  string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param  string  $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * Get the value of picture
     *
     * @return  string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set the value of picture
     *
     * @param  string  $picture
     */
    public function setPicture(string $picture)
    {
        $this->picture = $picture;
    }

    /**
     * Get the value of price
     *
     * @return  float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @param  float  $price
     */
    public function setPrice(int $price)
    {
        $this->price = $price;
    }

    /**
     * Get the value of rate
     *
     * @return  int
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set the value of rate
     *
     * @param  int  $rate
     */
    public function setRate(int $rate)
    {
        $this->rate = $rate;
    }

    /**
     * Get the value of status
     *
     * @return  int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param  int  $status
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    /**
     * Get the value of brand_id
     *
     * @return  int
     */
    public function getBrandId()
    {
        return $this->brand_id;
    }

    /**
     * Set the value of brand_id
     *
     * @param  int  $brand_id
     */
    public function setBrandId(int $brand_id)
    {
        $this->brand_id = $brand_id;
    }

    /**
     * Get the value of category_id
     *
     * @return  int
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Set the value of category_id
     *
     * @param  int  $category_id
     */
    public function setCategoryId(int $category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * Get the value of type_id
     *
     * @return  int
     */
    public function getTypeId()
    {
        return $this->type_id;
    }

    /**
     * Set the value of type_id
     *
     * @param  int  $type_id
     */
    public function setTypeId(int $type_id)
    {
        $this->type_id = $type_id;
    }
}
