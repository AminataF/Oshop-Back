<div class="container my-4">
    <a href="<?= $router->generate('product-list'); ?>" class="btn btn-success float-end">Retour sur la liste des produits</a>
    <h2>Modification d'un produit</h2>
    <form action="" method="POST" class="mt-5">
        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= $product->getName()?>" placeholder="Nom de la catégorie">
        </div>
        <div class="mb-3">
            <label for="subtitle" class="form-label">Sous-titre</label>
            <textarea class="form-control" id="description" name="description" aria-describedby="descriptionHelpBlock"><?= $product->getDescription() ?></textarea>
            <small id="subtitleHelpBlock" class="form-text text-muted">
                Sera affiché sur la page d'accueil comme bouton devant l'image
            </small>
        </div>
        <div class="mb-3">
            <label for="picture" class="form-label">Image</label>
            <input type="text" class="form-control" id="picture" name="picture" value="<?= $product->getPicture() ?? '' ?>" placeholder="image jpg, gif, svg, png" aria-describedby="pictureHelpBlock">
            <small id="pictureHelpBlock" class="form-text text-muted">
                URL relative d'une image (jpg, gif, svg ou png) fournie sur <a href="https://benoclock.github.io/S06-images/" target="_blank">cette page</a>
            </small>
        </div>
        <div class="mb-3">
            <label class="form-label" for="price">Prix</label>
            <input type="number" class="form-control" id="price" name="price" <?php if ($product->getPrice() !== 0) : ?>value="<?= $product->getPrice()?>" <?php endif; ?> placeholder="ex. 49.00" aria-describedby="priceHelpBlock">
            <small id="priceHelpBlock" class="form-text text-muted">
                Le prix du produit en euros
            </small>
            <!-- </div>
        <div class="mb-3">
            <label class="form-label" for="rate">Note</label>
            <input type="text" class="form-control" id="rate" name="rate" placeholder="ex. 3" aria-describedby="rateHelpBlock">
            <small id="rateHelpBlock" class="form-text text-muted">
                Le note du produit sur 5
            </small>
        </div> -->
            <div class="mb-3">
                <label class="form-label" for="status">Statut</label>
                <select class="form-select" id="status" name="status" aria-describedby="statusHelpBlock">
                    <option value="1" <?= $product->getStatus() == "1" ? 'selected' : '' ?>>Disponible</option>
                    <option value="2" <?= $product->getStatus() == "2" ? 'selected' : '' ?>>Indisponible</option>
                </select>
                </select>
                <small id="statusHelpBlock" class="form-text text-muted">
                    Le statut du produit
                </small>
            </div>
            <div class="mb-3">
                <label class="form-label" for="category">Categorie</label>
                <input type="text" class="form-control" id="category_id" name="category_id" placeholder="1" value="<?= $product->getCategoryId() ?>">
                <small id="categoryHelpBlock" class="form-text text-muted">
                    La catégorie du produit
                </small>
            </div>
            <div class="mb-3">
                <label class="form-label" for="brand">Marque</label>
                <input type="text" class="form-control" id="brand_id" name="brand_id" placeholder="1" value="<?= $product->getBrandId() ?>">
                <small id="brandHelpBlock" class="form-text text-muted">
                    La marque du produit
                </small>
            </div>
            <div class="mb-3">
                <label class="form-label" for="type">Type</label>
                <input type="text" class="form-control" id="type_id" name="type_id" placeholder="1" value="<?= $product->getTypeId() ?>">
                <small id="typeHelpBlock" class="form-text text-muted">
                    Le type de produit
                </small>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary mt-5">Valider</button>
            </div>
    </form>
</div>