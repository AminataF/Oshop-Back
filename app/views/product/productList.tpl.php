<div class="container my-4">
    <a href="<?= $router->generate('product-add'); ?>" class="btn btn-success float-end">Ajouter</a>
    <h2>Liste des produits</h2>
    <table class="table table-hover mt-4">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nom</th>
                <th scope="col">Sous-titre</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product) : ?>
            <tr>
                <th scope="row"><?= $product->getId()?></th>
                <td><?= $product->getName()?></td>
                <?php // on va générer l'URL de la "thumbnail" (vignette) via PHP ?>
                <?php // on remarque que : image.jpg => image_tn.jpg ?>
                <td><img class="product__picture-thumbnail" src="<?= str_replace('.jpg', '_tn.jpg', $product->getPicture()); ?>" alt="<?= $product->getName(); ?>"></td>
                <td>
                    <?php if ($product->getStatus() == 1): ?>
                        <span class="badge btn-success">Disponible</span>
                    <?php else: ?>
                        <span class="badge btn-warning">Indisponible</span>
                    <?php endif; ?>
                </td>
                <td class="text-end">
                    <a href="<?= $router->generate('product-edit', ['id' => $product->getId()]);?>" class="btn btn-sm btn-warning">
                        
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </a>
                    <!-- Example single danger button -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            delete
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= $router->generate('product-delete', ['id' => $product->getId()]);?>">Oui, je veux supprimer</a>
                            <a class="dropdown-item" href="#" data-toggle="dropdown">Oups !</a>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endforeach ;?>
        </tbody>
    </table>
</div>