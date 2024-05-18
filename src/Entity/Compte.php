<?php

namespace App\Entity;

use App\Repository\CompteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompteRepository::class)
 */
class Compte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numcarte;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codes;

    /**
     * @ORM\Column(type="float")
     */
    private $credit;

    /**
     * @ORM\Column(type="integer")
     */
    private $confirme;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $id_compte;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="compte")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumcarte(): ?string
    {
        return $this->numcarte;
    }

    public function setNumcarte(string $numcarte): self
    {
        $this->numcarte = $numcarte;

        return $this;
    }

    public function getCodes(): ?string
    {
        return $this->codes;
    }

    public function setCodes(string $codes): self
    {
        $this->codes = $codes;

        return $this;
    }

    public function getCredit(): ?float
    {
        return $this->credit;
    }

    public function setCredit(float $credit): self
    {
        $this->credit = $credit;

        return $this;
    }

    public function getConfirme(): ?int
    {
        return $this->confirme;
    }

    public function setConfirme(int $confirme): self
    {
        $this->confirme = $confirme;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIdCompte(): ?string
    {
        return $this->id_compte;
    }

    public function setIdCompte(?string $id_compte): self
    {
        $this->id_compte = $id_compte;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
