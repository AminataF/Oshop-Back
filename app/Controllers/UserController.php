<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\CoreModel;

class UserController extends CoreController
{
    public function login()
    {
        $this->show('user/login');
    }

    public function connect()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = User::findByEmail($email);
        // - cas 1 : user non trouvé => erreur sur le form
        if ($user === false) {
            echo 'Utilisateur non trouvé.';
        } else {
            // - cas 2 : user trouvé :tada:
            // - on vérifie que le mot de passe fourni correspond au mot de passe récupéré depuis la base
            // - avec password_hash, on hache le mot de passe avec l'algo BCRYPT
            // - avec password_verify(), on demande à l'algo BCRYPT de nous dire si les mots de passe correspond
            if (false === password_verify($password, $user->getPassword())) {
                //     - cas 1 : ne correspond pas => erreur sur le form
                echo 'Le mot de passe ne correspond pas.';
            } else {
                // - cas 2 : correspond !
                //echo 'Le mot de passe correspond.';
                // - on le "connecte" au serveur via la session
                // "userId" : l'id de l'utilisateur connecté
                $_SESSION['userId'] = $user->getId();
                // "userObject" : l'objet AppUser de l'utilisateur connecté
                $_SESSION['userObject'] = $user;
                // - on va échanger un cookie de session entre le client et le serveur
                // - on redirige vers la home
                header('Location: /');
                exit;
            }
        }
    }

    /**
     * Déconnexion/Logout
     */
    public function logout()
    {
        // unset() permet de supprimer une variable ou un index ou une clé de tableau
        // @see https://www.php.net/manual/en/function.unset
        // on "supprime" les clés de la session liées à ntre utilisateur
        unset($_SESSION['userId']);
        unset($_SESSION['userObject']);

        // redirection vers le login
        header('Location: /user/login');
        exit;
    }
    /**
     * Liste des utilisateurs
     * Il y a une restcrition sur la page seule les admins peuvent y avoir accés 
     */
    public function userList()
    {
        // nos utilisateurs
        $users = User::findAll();
        // dd($users);

        // on génère la vue
        $this->show('user/userList', [
            'users' => $users,
        ]);
    }

    /**
     *  Affichage du form en GET d'ajout d'un utilisateur 
     */
    public function userAdd()
    {
        // on crée un objet dont les propriétés sont vides
        $user = new User();

        // on génère la vue
        $this->show('user/userAdd', [
            'user' => $user,
        ]);
    }


    // j'ajoute en bdd un nouvelle utilisateur
    public function userCreate()
    {
        // dd($_POST);
        // dd($user);
        // on récupère les données dans des variables
        // on utilise l'opérateur de coalescence nulle ??
        // https://www.php.net/manual/en/migration70.new-features.php
        // cet opérateur nous permet de mettre la valeur de $_POST['name'] dans $name s'il est défini, sinon ''
        $lastname = $_POST['lastname'] ?? '';
        $firstname = $_POST['firstname'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? '';
        // on convertit la chaine en entier avant de la transmettre au setter du modèle
        // par ex. "" => 0
        $status = (int) $_POST['status'] ?? '';
        // on pourrait aussi faire comme ça :
        // if(isset($_POST['status'])) {$status = $status}
        

        // VALIDATION DES DONNÉES
        // @see https://github.com/O-clock-Nazca/S06-E02-atelier-ajout-DB/blob/master/mega_bonus.md

        // on créé un tableau pour y ajouter les erreurs éventuelles
        $errorList = [];

        // le nom ne doit pas être vide e et plus long que 3 caracteres
        if (empty($lastname) && strlen($lastname) > 3) {
            $errorList[] = "Le nom doit est requis.";
        }

        // le prénom ne doit pas être vide e et plus long que 3 caracteres
        if (empty($firstname) && strlen($firstname) > 3) {
            $errorList[] = "Le prénom doit est requis.";
        }

        // l'adresse mail doit être valide
        // @see https://www.php.net/manual/en/function.filter-var.php
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorList[] = "L'adresse email n'est pas valide.";
        }

        // on vérifier que l'adresse e-mail n'est pas déjà présente dans la base
        // => si AppUser::findByEmail() vaut autre chose que "false", il existe
        if (User::findByEmail($email) !== false) {
            $errorList[] = "Cette adresse mail existe déjà dans la base.";
        }

        // le mot de passe ne doit pas être vide et plus long que 10 caracteres
        if (empty($password)) {
            $errorList[] = "Le mot de passe est requis.";
        }

        // mot de passe "compliqué" voir les regex
        // https://regexone.com/
        // https://regexcrossword.com/
        // https://regex101.com/
        if (!preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/', $password)) {
            $errorList[] = "Le mot de passe doit contenir les caractères démandés.";
        }

        // le rôle ne doit pas être vide
        if (empty($role)) {
            $errorList[] = "Le rôle est requis.";
        }

        // si rôle non vide, il doit être une valeur valide
        if (!empty($role) && !in_array($role, ['catalog-manager', 'admin'])) {
            $errorList[] = "Le rôle est invalide.";
        }

        // le statut ne doit pas être vide
        if (empty($status)) {
            $errorList[] = "Le statut est requis.";
        }

        // si non vide, statut compris entre 1 et 2 inclus
        if (!empty($status) && ($status < 1 || $status > 2)) {
            $errorList[] = "Le statut est invalide.";
        }

        // création de l'utilisateur
        $user = new User();
        // on vérifie si on a rencontré une erreur ou non
        if (empty($errorList)) {
            // on alimente cet objet avec les donneés de la requête (on remplit ses propriétés)
            $user->setLastname($lastname);
            $user->setFirstname($firstname);
            $user->setEmail($email);
            $user->setPassword($password);
            $user->setRole($role);
            $user->setStatus($status);
            // si le tableau errorList est vide, ça veut dire qu'il n'y a pas d'erreur
            // donc on peut ajouter à la DB !

            // on hâche le mot de passe reçu avec l'algo bcrypt
            // juste avant de le sauvegarder
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $user->setPassword($hashedPassword);

            // on dit à cet objet de s'insérer dans la base !
            // save() renvoit true si l'ajout a fonctionné, false sinon
            $success = $user->save();
            // dump($category);

            if ($success) {
                // @see https://www.php.net/manual/fr/function.header.php
                // redirection vers la liste des catégories

                // on va utiliser le mot-clé "global" pour accéder au routeur
                // car on préfère générer la route que de l'écrire en dur
                // @todo trouver une solution plus orientée POO pour accéder à ce routeur
                // /!\ ne pas afficher quoique ce soit avant (echo, dump(), etc.)
                global $router;
                header('Location: /user/userList');
                exit;
            } else {
                // ça n'a pas fonctionné, on met un message d'erreur, et le script continue après le if
                $errorList[] = "Erreur lors de l'ajout.";
            }
        }

        // si on arrive là, c'est qu'il y a eu une erreur
        // on réaffiche le formulaire, mais pré-rempli avec les (mauvaises) données saisies dans $_POST

        // on affiche à nouveau de form d'ajout, mais avec les erreurs & les données erronées
        $this->show('user/userAdd', [
            'errorList' => $errorList,
            // le form attends un objet pour pré-remplir ses valeurs
            'user' => $user,
        ]);
    }
}
