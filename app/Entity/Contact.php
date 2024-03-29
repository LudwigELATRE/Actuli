<?php

namespace App\Entity;

class Contact
{
    private string $name;
    private string $subject;
    private string $message;

    public function __construct($name,$subject,$message)
    {
        $this->name = $name;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}