<?php

namespace App\Entity;

use App\Repository\CompteBancaireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompteBancaireRepository::class)
 */
class CompteBancaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Utilisateur::class, inversedBy="compteBancaire", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_compte;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numero_carte;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code_securite;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $solde;

    /**
     * @ORM\Column(type="integer")
     */
    private $confirme;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCompte(): ?Utilisateur
    {
        return $this->id_compte;
    }

    public function setIdCompte(Utilisateur $id_compte): self
    {
        $this->id_compte = $id_compte;

        return $this;
    }

    public function getNumeroCarte(): ?string
    {
        return $this->numero_carte;
    }

    public function setNumeroCarte(?string $numero_carte): self
    {
        $this->numero_carte = $numero_carte;

        return $this;
    }

    public function getCodeSecurite(): ?string
    {
        return $this->code_securite;
    }

    public function setCodeSecurite(?string $code_securite): self
    {
        $this->code_securite = $code_securite;

        return $this;
    }

    public function getSolde(): ?float
    {
        return $this->solde;
    }

    public function setSolde(?float $solde): self
    {
        $this->solde = $solde;

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

    public function getStatus(): ?int {
        return $this->status;
    }

    public function setStatus(int $status): self {
        $this->status = $status;
        return $this;
    }

}
