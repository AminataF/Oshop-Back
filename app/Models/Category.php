<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Category extends CoreModel
{

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $subtitle;
    /**
     * @var string
     */
    private $picture;
    /**
     * @var int
     */
    private $home_order;


    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `category`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Category');

        return $results;
    }

    /**
     * Récupérer les 5 catégories mises en avant sur la home
     *
     * @return Category[]
     */
    public function findAllHomepage()
    {
        $pdo = Database::getPDO();
        $sql = '
            SELECT *
            FROM category
            WHERE home_order > 0
            ORDER BY home_order ASC
        ';
        $pdoStatement = $pdo->query($sql);
        $categories = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Category');

        return $categories;
    }
    /**
     * Méthode permettant de récupérer tous les enregistrements de la table category
     *
     * @return Category[]
     */

    public static function findAllOrderedByNameAsc()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `category` ORDER BY `name` ASC';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Category');

        return $results;
    }

    /**
     * Méthode permettant de récupérer un enregistrement de la table Category en fonction d'un id donné
     *
     * @param int $categoryId ID de la catégorie
     * @return Category
     */
    public static function find($categoryId)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = 'SELECT * FROM `category` WHERE `id` =' . $categoryId;

        // exécuter notre requête
        $pdoStatement = $pdo->query($sql);

        // un seul résultat => fetchObject
        $category = $pdoStatement->fetchObject('App\Models\Category');

        // retourner le résultat
        return $category;
    }

    public static function findLastFive(){

        $pdo = Database::getPDO();
        $sql = 'SELECT category.id, category.name
        FROM `category`
        ORDER BY id DESC
        LIMIT 5';

        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Category');

        return $results;
    }

    public function insert(){
        
        $pdo = Database::getPDO();
        $pdoStatement = $pdo->prepare("
                        INSERT INTO category (name, subtitle, picture) 
                        VALUES (:name, :subtitle, :picture )");
        $pdoStatement->bindParam(':name', $this->name, PDO::PARAM_STR);
        $pdoStatement->bindParam(':subtitle', $this->subtitle, PDO::PARAM_STR);
        $pdoStatement->bindParam(':picture', $this->picture, PDO::PARAM_STR);
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


    public function update(){

        $pdo = Database::getPDO();
        $pdoStatement = $pdo->prepare("
                            UPDATE category SET name = :name, subtitle = :subtitle,
                            picture = :picture, home_order = :home_order WHERE id = :id ");
        $pdoStatement->bindParam(':name', $this->name, PDO::PARAM_STR);
        $pdoStatement->bindParam(':subtitle', $this->subtitle, PDO::PARAM_STR);
        $pdoStatement->bindParam(':picture', $this->picture, PDO::PARAM_STR);
        $pdoStatement->bindValue(':home_order', $this->home_order, PDO::PARAM_INT);
        $pdoStatement->bindParam(':id', $this->id, PDO::PARAM_INT);
        $success = $pdoStatement->execute();

        return $success;
    }

    public function delete(){
        
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // on écrit notre requête SQL
        $sql = "DELETE FROM category WHERE id = :id";

        // on prépare cette requête
        $pdoStatement = $pdo->prepare($sql);

        // on bind nos paramètres
        $pdoStatement->bindParam(":id", $this->id);

        // on exécute la requête
        $category = $pdoStatement->execute();

        // on retourne true ou false en fonction de si la requête s'est bien passée ou pas !
        return $category;
    
    }

    /**
     * Met à 0 les home_order de toutes les catégories
     */
    public static function resetHomeOrder()
    {
        $pdo = Database::getPDO();
        $sql = 'UPDATE `category` SET home_order = 0';
        $nbRows = $pdo->exec($sql);

        return $nbRows;
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
     * Get the value of subtitle
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set the value of subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * Get the value of picture
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set the value of picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * Get the value of home_order
     */
    public function getHomeOrder()
    {
        return $this->home_order;
    }

    /**
     * Set the value of home_order
     */
    public function setHomeOrder($home_order)
    {
        $this->home_order = $home_order;
    }

    
}
