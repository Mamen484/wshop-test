<?php

declare(strict_types=1);

namespace FwTest\Classes;

use FwTest\Core\Database;
use FwTest\Model\ProductDTO;

class ProductDAO
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Retourne la liste des produits
     */
    public function findAll(int $offset = 0, int $limit = 20): array
    {
        $sql = "SELECT * FROM product ORDER BY updated_at DESC LIMIT :offset, :limit";
        $pdo = $this->db->getPdo();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll();
        return array_map(fn($row) => new ProductDTO($row), $rows);
    }

    /**
     * Trouve un produit par son ID
     */
    public function findById(int $id): ?ProductDTO
    {
        $sql = "SELECT * FROM product WHERE id = :id";
        $row = $this->db->fetchRow($sql, ['id' => $id]);
        return $row ? new ProductDTO($row) : null;
    }

    /**
     * Supprime un produit
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM product WHERE id = :id";
        return $this->db->query($sql, ['id' => $id]);
    }

    /**
     * Crée un nouveau produit
     */
    public function create(ProductDTO $product): bool
    {
        $sql = "INSERT INTO product (reference, name, title, price, discount_price, description, updated_at)
                VALUES (:reference, :name, :title, :price, :discount_price, :description, NOW())";

        return $this->db->query($sql, [
            'reference' => $product->getReference(),
            'name' => $product->getName(),
            'title' => $product->getTitle(),
            'price' => $product->getPrice(),
            'discount_price' => $product->getDiscountPrice(),
            'description' => $product->getDescription(),
        ]);
    }

    /**
     * Met à jour un produit existant
     */
    public function update(ProductDTO $product): bool
    {
        if (!$product->getId()) {
            throw new \InvalidArgumentException('Product ID is required for update.');
        }

        $sql = "UPDATE product 
                SET reference = :reference,
                    name = :name,
                    title = :title,
                    price = :price,
                    discount_price = :discount_price,
                    description = :description,
                    updated_at = NOW()
                WHERE id = :id";

        return $this->db->query($sql, [
            'id' => $product->getId(),
            'reference' => $product->getReference(),
            'name' => $product->getName(),
            'title' => $product->getTitle(),
            'price' => $product->getPrice(),
            'discount_price' => $product->getDiscountPrice(),
            'description' => $product->getDescription(),
        ]);
    }
}
