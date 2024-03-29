<?php

namespace App\Entity;

class Category
{
    private string $name;
    private string $description;
    private string $slug;
    private string $createdAt;
    private string $updatedAt;

    public function __construct(string $name,string $description,string $slug, \DateTimeImmutable $createdAt)
    {
        $this->name = $name;
        $this->description = $description;
        $this->slug = $slug;
        $this->createdAt = $createdAt->format('Y-m-d');
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}