<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
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
    private $destinaire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $destinataire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_destinaire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_destinataire;

    /**
     * @ORM\Column(type="float")
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity=CompteBancaire::class, inversedBy="transaction")
     * @ORM\JoinColumn(nullable=false)
     */
    private $compte_destinaire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDestinaire(): ?string
    {
        return $this->destinaire;
    }

    public function setDestinaire(string $destinaire): self
    {
        $this->destinaire = $destinaire;

        return $this;
    }

    public function getDestinataire(): ?string
    {
        return $this->destinataire;
    }

    public function setDestinataire(string $destinataire): self
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNomDestinaire(): ?string
    {
        return $this->nom_destinaire;
    }

    public function setNomDestinaire(string $nom_destinaire): self
    {
        $this->nom_destinaire = $nom_destinaire;

        return $this;
    }

    public function getNomDestinataire(): ?string
    {
        return $this->nom_destinataire;
    }

    public function setNomDestinataire(string $nom_destinataire): self
    {
        $this->nom_destinataire = $nom_destinataire;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getCompteDestinaire(): ?CompteBancaire
    {
        return $this->compte_destinaire;
    }

    public function setCompteDestinaire(?CompteBancaire $compte_destinaire): self
    {
        $this->compte_destinaire = $compte_destinaire;

        return $this;
    }
}
