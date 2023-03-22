<div class="container my-4">

    <h2>Catégories mises en avant sur la page d'accueil</h2>

    <form action="" method="POST" class="mt-5">
        <div class="row">
            <div class="col">
                <div class="mb-3">
                    <label class="form-label" for="emplacement1">Emplacement #1</label>
                    <select class="form-select" id="emplacement1" name="emplacement[]">
                        <?php // option value = id de chaque catégorie, option text = nom de la catégorie ?>
                        <?php foreach ($categories as $category) : ?>
                        <?php // on affiche "selected" si le home order de la catégorie est celui de l'emplacement actuel (1, 2, 3, 4 ou 5) ?>
                        <option value="<?= $category->getId() ?>" <?= $category->getHomeOrder() == 1 ? 'selected' : '' ?>><?= $category->getName() ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label class="form-label" for="emplacement2">Emplacement #2</label>
                    <select class="form-select" id="emplacement2" name="emplacement[]">
                        <?php // option value = id de chaque catégorie, option text = nom de la catégorie ?>
                        <?php foreach ($categories as $category) : ?>
                        <?php // on affiche "selected" si le home order de la catégorie est celui de l'emplacement actuel (1, 2, 3, 4 ou 5) ?>
                        <option value="<?= $category->getId() ?>" <?= $category->getHomeOrder() == 2 ? 'selected' : '' ?>><?= $category->getName() ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="mb-3">
                    <label class="form-label" for="emplacement3">Emplacement #3</label>
                    <select class="form-select" id="emplacement3" name="emplacement[]">
                        <?php // option value = id de chaque catégorie, option text = nom de la catégorie ?>
                        <?php foreach ($categories as $category) : ?>
                        <?php // on affiche "selected" si le home order de la catégorie est celui de l'emplacement actuel (1, 2, 3, 4 ou 5) ?>
                        <option value="<?= $category->getId() ?>" <?= $category->getHomeOrder() == 3 ? 'selected' : '' ?>><?= $category->getName() ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label class="form-label" for="emplacement4">Emplacement #4</label>
                    <select class="form-select" id="emplacement4" name="emplacement[]">
                        <?php // option value = id de chaque catégorie, option text = nom de la catégorie ?>
                        <?php foreach ($categories as $category) : ?>
                        <?php // on affiche "selected" si le home order de la catégorie est celui de l'emplacement actuel (1, 2, 3, 4 ou 5) ?>
                        <option value="<?= $category->getId() ?>" <?= $category->getHomeOrder() == 4 ? 'selected' : '' ?>><?= $category->getName() ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label class="form-label" for="emplacement5">Emplacement #5</label>
                    <select class="form-select" id="emplacement5" name="emplacement[]">
                        <?php // option value = id de chaque catégorie, option text = nom de la catégorie ?>
                        <?php foreach ($categories as $category) : ?>
                        <?php // on affiche "selected" si le home order de la catégorie est celui de l'emplacement actuel (1, 2, 3, 4 ou 5) ?>
                        <option value="<?= $category->getId() ?>"><?= $category->getName() ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
    </form>
</div>