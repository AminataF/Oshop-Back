<?php // l'URL étant la même que pour afficher le form, pas besoin d'action, mais si besoin on peut générer l'URL avec le routeur 
?>
<div class="container my-4">
    <a href="<?= $router->generate('user-list'); ?>" class="btn btn-success float-end">Retour</a>
    <h2>Ajouter un utilisateur</h2>

    <!-- on inclut les erreurs -->
    <?php include __DIR__ . '/../partials/errors.tpl.php'; ?>

    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label for="lastname" class="form-label">Nom</label>
            <input type="text" class="form-control" name="lastname" id="lastname" placeholder="ex. Dupont" value="<?= $user->getLastname() ?? '' ?>">
        </div>
        <div class="mb-3">
            <label for="firstname" class="form-label">Prénom</label>
            <input type="text" class="form-control" name="firstname" id="firstname" placeholder="ex. Robert" value="<?= $user->getFirstname() ?? '' ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="ex. robert@dupont.fr" value="<?= $user->getEmail() ?? '' ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="" value="<?= $user->getPassword() ?? '' ?>">
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Rôle</label>
            <select class="form-select" aria-label="Default select example" name="role" value="<?= $user->getRole() ?? '' ?>" id="role">
                <option>Choix...</option>
                <option value="admin">Administrateur</option>
                <option value="catalog-manager">Gestionnaire du catalogue</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Statut</label>
            <select class="form-select" aria-label="Default select example" name="status" value="<?= $user->getStatus() ?? '' ?>" id="status">
                <option>Choix...</option>
                <option value="1">Actif</option>
                <option value="2">Désactivé</option>
            </select>
        </div>

        <div>
            <input type="hidden" name="csrf_token" value="<?= $this->generateToken(); ?>">
            <button type="submit" class="btn btn-primary mt-5">Valider</button>
        </div>
    </form>
</div>