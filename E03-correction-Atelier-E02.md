# Correction atelier e02

- *Etape 1.* Afficher le formulaire d'ajout d'une catégorie.
- Créer une route en POST pour récupérer les données soumises par le form.
  - et la méthode de contrôleur associée `create`.
  - on récupèrera chaque donnée de $_POST dans une variable.
    - il manquait les attributs `name` sur les input du form fourni.
    - la valeur de ces attributs `name` correspond aux propriétés du Model concerné (ici Category) (et donc aux colonnes de la table SQL associée).
- *Etape 2.* Créer une nouvel object Category et l'ajouter en base.
- On crée une catégorie depuis le modèle Category
- On renseigne ses propriétés via les setters.
- On crée dans le modèle Category une méthode `insert()` inspirée de celle présente dans `Brand` pour sauvegarder dans la base.
- *Etape 4.* On redirige vers la liste de catégories via `header()`.