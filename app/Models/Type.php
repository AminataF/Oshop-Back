<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

/**
 * Un modèle représente une table (un entité) dans notre base
 *
 * Un objet issu de cette classe réprésente un enregistrement dans cette table
 */
class Type extends CoreModel
{
    // Les propriétés représentent les champs
    // Attention il faut que les propriétés aient le même nom (précisément) que les colonnes de la table

    /**
     * @var string
     */
    private $name;

    /**
     * Méthode permettant de récupérer un enregistrement de la table Type en fonction d'un id donné
     *
     * @param int $typeId ID du type
     * @return Type
     */
    public static function find($typeId)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = 'SELECT * FROM `type` WHERE `id` =' . $typeId;

        // exécuter notre requête
        $pdoStatement = $pdo->query($sql);

        // un seul résultat => fetchObject
        $type = $pdoStatement->fetchObject('App\Models\Type');

        // retourner le résultat
        return $type;
    }

    /**
     * Méthode permettant de récupérer tous les enregistrements de la table type
     *
     * @return Type[]
     */
    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `type`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Type');

        return $results;
    }

    public function insert(){
        
        $pdo = Database::getPDO();
        $pdoStatement = $pdo->prepare("
                        INSERT INTO type (name) 
                        VALUES (:name)");
        $pdoStatement->bindParam(':name', $this->name, PDO::PARAM_STR);
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
                            UPDATE type SET name = :name WHERE id = :id");
        $pdoStatement->bindParam(':name', $this->name, PDO::PARAM_STR);
        $pdoStatement->bindParam(':id', $this->id, PDO::PARAM_INT);
        $success = $pdoStatement->execute();

        return $success;
    }

    public function delete(){
        
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // on écrit notre requête SQL
        $sql = "DELETE FROM type WHERE id = :id";

        // on prépare cette requête
        $pdoStatement = $pdo->prepare($sql);

        // on bind nos paramètres
        $pdoStatement->bindParam(":id", $this->id);

        // on exécute la requête
        $success = $pdoStatement->execute();

        // on retourne true ou false en fonction de si la requête s'est bien passée ou pas !
        return $success;
    
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
}
