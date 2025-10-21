<?php

declare(strict_types=1);

namespace FwTest\Model;

/**
 * Data Transfer Object (DTO) pour un produit
 */
class ProductDTO
{
    public ?int $id;
    public string $reference;
    public string $name;
    public ?string $title;
    public float $price;
    public float $discount_price;
    public string $description;
    public ?string $updated_at;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->reference = $data['reference'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->title = $data['title'] ?? null;
        $this->price = (float)($data['price'] ?? 0);
        $this->discount_price = (float)($data['discount_price'] ?? 0);
        $this->description = $data['description'] ?? '';
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public function getId(): ?int { return $this->id; }
    public function getReference(): string { return $this->reference; }
    public function getName(): string { return $this->name; }
    public function getTitle(): ?string { return $this->title; }
    public function getPrice(): float { return $this->price; }
    public function getDiscountPrice(): float { return $this->discount_price; }
    public function getDescription(): string { return $this->description; }
    public function getUpdatedAt(): ?string { return $this->updated_at; }
}
