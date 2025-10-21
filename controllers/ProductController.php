<?php

declare(strict_types=1);

namespace FwTest\Controller;

use FwTest\Classes\ProductDAO;
use FwTest\Model\ProductDTO;

class ProductController extends AbstractController
{
    /**
     * @Route(
     *     path="/product_list",
     *     name="get_product_list",
     *     methods={"GET"},
     * )
     */
    public function index(): void
    {
        $db = $this->getDatabaseConnection();
        $dao = new ProductDAO($db);

        $limit = (int)($this->array_constant['product']['nb_products'] ?? 15);
        $list_product = $dao->findAll(0, $limit);

        echo $this->render('product/list.tpl', ['list_product' => $list_product]);
    }

    /**
     * @Route(
     *     path="/product_detail",
     *     name="get_product_detail",
     *     methods={"GET"},
     * )
     */
    public function detail(): void
    {
        $db = $this->getDatabaseConnection();
        $dao = new ProductDAO($db);

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            $this->_redirect('/product_list');
        }

        $product = $dao->findById($id);

        if (!$product) {
            $this->_redirect('/product_list');
        }

        echo $this->render('product/detail.tpl', ['product' => $product]);
    }

    /**
     * @Route(
     *     path="/product_add",
     *     name="add_product",
     *     methods={"GET", "POST"},
     * )
     */
    public function create(): void
    {
        $db = $this->getDatabaseConnection();
        $dao = new ProductDAO($db);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product = new ProductDTO([
                'reference'      => $_POST['reference'] ?? '',
                'name'           => $_POST['name'] ?? '',
                'title'          => $_POST['title'] ?? '',
                'price'          => (float)($_POST['price'] ?? 0),
                'discount_price' => (float)($_POST['discount_price'] ?? 0),
                'description'    => $_POST['description'] ?? '',
            ]);

            if ($dao->create($product)) {
                $this->_redirect('/product_list');
            }

            echo $this->render('product/form.tpl', [
                'error' => 'Erreur lors de la création du produit',
                'product' => $product
            ]);
            return;
        }

        echo $this->render('product/form.tpl');
    }

    /**
     * @Route(
     *     path="/product_edit",
     *     name="edit_product",
     *     methods={"GET", "POST"},
     * )
     */
    public function edit(): void
    {
        $db = $this->getDatabaseConnection();
        $dao = new ProductDAO($db);

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $product = $dao->findById($id);

        if (!$product) {
            $this->_redirect('/product_list');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updatedProduct = new ProductDTO([
                'id'             => $id,
                'reference'      => $_POST['reference'] ?? $product->getReference(),
                'name'           => $_POST['name'] ?? $product->getName(),
                'title'          => $_POST['title'] ?? $product->getTitle(),
                'price'          => (float)($_POST['price'] ?? $product->getPrice()),
                'discount_price' => (float)($_POST['discount_price'] ?? $product->getDiscountPrice()),
                'description'    => $_POST['description'] ?? $product->getDescription(),
            ]);

            if ($dao->update($updatedProduct)) {
                $this->_redirect('/product_detail?id=' . $id);
            }

            echo $this->render('product/form.tpl', [
                'error' => 'Erreur lors de la mise à jour du produit',
                'product' => $updatedProduct
            ]);
            return;
        }

        echo $this->render('product/form.tpl', ['product' => $product]);
    }

    /**
     * @Route(
     *     path="/product_delete",
     *     name="delete_product",
     *     methods={"POST"},
     * )
     */
    public function delete(): void
    {
        $db = $this->getDatabaseConnection();
        $dao = new ProductDAO($db);

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

        if ($id > 0) {
            $dao->delete($id);
        }

        $this->_redirect('/product_list');
    }
}
