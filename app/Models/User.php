<?php

namespace App\Models;

use App\Utils\Database;
use App\Models\CoreModel;
use \PDO;

class User extends CoreModel
{
    private $email;
    private $password;
    private $firstname;
    private $lastname;
    private $role;
    private $status;

    // TODO: AJOUT DU ROLE ET STATUT DANS MES FUNCTION INSERT ET UPDATE
    public static function find(int $id)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // dans la mesure où $id est de type int, on ne pourra pas injecter de code ici
        // donc pas de requête préparée ? par sécurité on pourrait le faire...
        // écrire notre requête
        $sql = 'SELECT * FROM `app_user` WHERE `id` =' . $id;

        // exécuter notre requête
        $pdoStatement = $pdo->query($sql);

        // un seul résultat => fetchObject
        $appUser = $pdoStatement->fetchObject('App\Models\User');

        // retourner le résultat
        return $appUser;
    }

    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `app_user`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\User');

        return $results;
    }

    public function insert()
    {
        $pdo = Database::getPDO();

        $sql = "
        INSERT INTO `app_user` (email, password, firstname, lastname, role, status)
        VALUES (:email, :password, :firstname, :lastname, :role, :status);
    ";

        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->bindValue(':email', $this->email, PDO::PARAM_STR);
        $pdoStatement->bindValue(':password', $this->password, PDO::PARAM_STR);
        $pdoStatement->bindValue(':firstname', $this->firstname, PDO::PARAM_STR);
        $pdoStatement->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
        $pdoStatement->bindValue(':role', $this->role, PDO::PARAM_STR);
        $pdoStatement->bindValue(':status', $this->status, PDO::PARAM_INT);

        $pdoStatement->execute();

        if ($pdoStatement->rowCount() > 0) {
            $this->id = $pdo->lastInsertId();

            return true;
        }

        return false;
    }

    public function update()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête UPDATE
        $sql = "
        UPDATE `app_user`

        SET email = :email,
        password = :password,
        firstname = :firstname,
        lastname = :lastname,
        role = :role,
        status = :status

        WHERE id = :id;
        ";;

        // Execution de la requête de mise à jour (exec, pas query)
        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->bindValue(':email', $this->email, PDO::PARAM_STR);
        $pdoStatement->bindValue(':password', $this->password, PDO::PARAM_STR);
        $pdoStatement->bindValue(':firstname', $this->firstname, PDO::PARAM_STR);
        $pdoStatement->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
        $pdoStatement->bindValue(':role', $this->role, PDO::PARAM_STR);
        $pdoStatement->bindValue(':status', $this->status, PDO::PARAM_INT);
        $pdoStatement->bindValue(':id', $this->id, PDO::PARAM_INT);

        $success = $pdoStatement->execute();

        // retourne si échec ou réussite de la requête
        return $success;
    }

    public function delete()
    {
        $pdo = Database::getPDO();

        $sql = "
            DELETE FROM `app_user`
            WHERE id = :id
        ";

        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->bindValue(':id', $this->id, PDO::PARAM_INT);
        $success = $pdoStatement->execute();

        return $success;
    }



    public static function findByEmail($email)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = "SELECT * FROM `app_user` WHERE `email` = :email";

        // exécuter notre requête
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->bindValue(':email', $email, PDO::PARAM_STR);
        $pdoStatement->execute();
        // un seul résultat => fetchObject
        $user = $pdoStatement->fetchObject('App\Models\User');

        // retourner le résultat
        return $user;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get the value of firstname
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @return  self
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get the value of lastname
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @return  self
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get the value of role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the value of role
     *
     * @return  self
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * Get the value of status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;
    }
}
