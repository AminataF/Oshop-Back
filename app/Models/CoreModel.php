<?php

namespace App\Models;

// Classe mère de tous les Models
// On centralise ici toutes les propriétés et méthodes utiles pour TOUS les Models

// CoreModel est une classe abstraite (abstract), ça veut dire qu'on ne pourra pas l'instancier !
abstract class CoreModel
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $created_at;
    /**
     * @var string
     */
    protected $updated_at;

    // Les classes enfants devront obligatoirement implémenter
    // les méthodes abstraites de la Classe Coremodel (find, findAll)
    // => permet de rendre notre cohérent, plus homogène
    abstract public static function find(int $id);
    abstract public static function findAll();
    abstract public function insert();
    abstract public function update();
    abstract public function delete();
    
    public function save()
    {
        // si le modèle courant a un id supérieur à 0, c'est qu'il a déjà été enregistré en base
        // donc on veut faire un update
        if ($this->getId() > 0) {
            return $this->update();
        }
        // sinon, c'est que le modèle n'a jamais été enregistré, donc on veut le créer en base
        else {
            return $this->insert();
        }
    }

    /**
     * Get the value of id
     *
     * @return  int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of created_at
     *
     * @return  string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * Get the value of updated_at
     *
     * @return  string
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

}
