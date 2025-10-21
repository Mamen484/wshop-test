<?php
/** @var FwTest\DTO\ProductDTO[] $list_product */
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>FW TEST - Liste des produits</title>

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

  <link href="css/main.css" rel="stylesheet">
</head>

<body>

  <div class="container my-5">
    <header class="mb-5 text-center">
      <h1 class="display-4">Nos produits</h1>
      <p class="lead text-muted">Découvrez notre sélection de produits.</p>
    </header>

    <div class="row">
      <?php if (!empty($list_product)): ?>
        <?php foreach ($list_product as $product): ?>
          <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
              <img class="card-img-top" src="https://placehold.co/500x325" alt="<?= htmlspecialchars($product->getName()) ?>">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($product->getTitle() ?? $product->getName()) ?></h5>
                <p class="card-text text-muted"><?= htmlspecialchars(substr($product->getDescription(), 0, 80)) ?>...</p>
                <p class="mb-1">
                  <?php if ($product->getDiscountPrice() > 0 && $product->getDiscountPrice() < $product->getPrice()): ?>
                    <strong class="text-success"><?= number_format($product->getDiscountPrice(), 2, ',', ' ') ?> €</strong>
                    <small class="text-muted"><del><?= number_format($product->getPrice(), 2, ',', ' ') ?> €</del></small>
                  <?php else: ?>
                    <strong><?= number_format($product->getPrice(), 2, ',', ' ') ?> €</strong>
                  <?php endif; ?>
                </p>
              </div>
              <div class="card-footer bg-transparent border-0">
                <a href="/product_detail?id=<?= $product->getId() ?>" class="btn btn-primary btn-block">Voir le produit</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center">
          <p class="text-muted">Aucun produit disponible pour le moment.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

</body>
</html>
