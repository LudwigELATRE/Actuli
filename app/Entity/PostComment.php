<?php

namespace App\Entity;

class PostComment
{
    private ?int $id;
    private ?int $user_id;
    private ?int $post_id;
    private ?string $author;
    private ?string $email;
    private ?string $content;
    private ?string $createdAt;

    public function __construct($user_id,$post_id,$author,$email,$content,\DateTimeImmutable $createdAt)
    {
        $this->user_id = $user_id;
        $this->post_id = $post_id;
        $this->author = $author;
        $this->email = $email;
        $this->content = $content;
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

    public function getPostId(): ?int
    {
        return $this->post_id;
    }

    public function setPostId(?int $post_id): void
    {
        $this->post_id = $post_id;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): void
    {
        $this->author = $author;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}