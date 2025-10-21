<?php
/** @var FwTest\DTO\ProductDTO $product */
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>FW TEST - Détail du produit</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link href="css/main.css" rel="stylesheet">
</head>

<body>

  <div class="container my-5">
    <a href="/product_list" class="btn btn-link mb-3">&larr; Retour à la liste</a>

    <div class="card shadow-sm">
      <img class="card-img-top" src="https://placehold.co/900x400" alt="<?= htmlspecialchars($product->getName()) ?>">

      <div class="card-body">
        <h2 class="card-title mb-3"><?= htmlspecialchars($product->getTitle() ?? $product->getName()) ?></h2>

        <h4 class="mb-4">
          <?php if ($product->getDiscountPrice() > 0 && $product->getDiscountPrice() < $product->getPrice()): ?>
            <span class="text-success"><?= number_format($product->getDiscountPrice(), 2, ',', ' ') ?> €</span>
            <span class="text-muted"><del><?= number_format($product->getPrice(), 2, ',', ' ') ?> €</del></span>
          <?php else: ?>
            <span><?= number_format($product->getPrice(), 2, ',', ' ') ?> €</span>
          <?php endif; ?>
        </h4>

        <p class="card-text"><?= nl2br(htmlspecialchars($product->getDescription())) ?></p>
      </div>

      <div class="card-footer bg-light text-right">
        <small class="text-muted">Dernière mise à jour :
          <?= $product->getUpdatedAt() ? $product->getUpdatedAt()->format('d/m/Y H:i') : 'N/A' ?>
        </small>
      </div>
    </div>
  </div>

</body>
</html>
