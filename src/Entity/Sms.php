<?php

namespace App\Entity;

use App\Repository\SmsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SmsRepository::class)]
class Sms
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $sender = null;

    #[ORM\Column(length: 255)]
    private ?string $receiver = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $data = null;

    #[ORM\Column]
    private ?int $idMail = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }
    public function getIdMail(): ?int
    {
        return $this->idMail;
    }

    public function setIdMail(int $idMail): static
    {
        $this->idMail = $idMail;

        return $this;
    }
    public function getSender(): ?string
    {
        return $this->sender;
    }

    public function setSender(string $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReceiver(): ?string
    {
        return $this->receiver;
    }

    public function setReceiver(string $receiver): static
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getData(): ?\DateTimeInterface
    {
        return $this->data;
    }

    public function setData(\DateTimeInterface $data): static
    {
        $this->data = $data;

        return $this;
    }
    public function setFromArray(array $mail): static
    {
        $this->setSender($mail['sender']) ;
        $this->setReceiver($mail['receiver']) ;
        $this->setMessage($mail['message']) ; 
        $this->setData(date_create($mail['data'])); 
        $this->setId($mail['id']); 
        return $this;
    }
}
