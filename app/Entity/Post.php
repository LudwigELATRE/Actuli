<?php

namespace App\Entity;

class Post
{
    private ?int $id;
    private ?int $user_id;
    private ?string $category;
    private ?string $title;
    private ?string $content;
    private ?string $slug;
    private ?string $image;
    private ?bool $published;
    private ?string $createdAt;
    private ?string $updatedAt;

    public function __construct(?int $user_id,string $category,string $title,string  $content,string $slug,string $image,bool $published,\DateTimeImmutable $createdAt)
    {
        $this->user_id = $user_id;
        $this->category = $category;
        $this->title = $title;
        $this->content = $content;
        $this->slug = $slug;
        $this->image = $image;
        $this->published = $published;
        $this->createdAt = $createdAt->format('Y-m-d');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(?int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): void
    {
        $this->category = $category;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getPublished(): ?string
    {
        return $this->published;
    }

    public function setPublished(?string $published): void
    {
        $this->published = $published;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt->format('Y-m-d');
    }

}