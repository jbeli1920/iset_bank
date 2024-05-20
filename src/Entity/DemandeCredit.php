<?php

namespace App\Entity;

use App\Repository\DemandeCreditRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DemandeCreditRepository::class)
 */
class DemandeCredit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $montant;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=CompteBancaire::class, inversedBy="demandeCredits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $compte;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbr_mois;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $raison;


    /**
     * @ORM\Column(type="integer")
     */
    private $montant_mois;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

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

    public function getCompte(): ?CompteBancaire
    {
        return $this->compte;
    }

    public function setCompte(?CompteBancaire $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getNbrMois(): ?int
    {
        return $this->nbr_mois;
    }

    public function setNbrMois(int $nbr_mois): self
    {
        $this->nbr_mois = $nbr_mois;

        return $this;
    }

    public function getMontantMois(): ?int
    {
        return $this->montant_mois;
    }

    public function setMontantMois(int $montant_mois): self
    {
        $this->montant_mois = $montant_mois;

        return $this;
    }

    public function getRaison(): ?string {
        return $this->raison;
    }

    public function setRaison(string $raison): self {
        $this->raison = $raison;
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
