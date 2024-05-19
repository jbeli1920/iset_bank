<?php

namespace App\Entity;

use App\Repository\CompteBancaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="compte_destinaire", orphanRemoval=true)
     */
    private $transaction;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->transaction = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransaction(): Collection
    {
        return $this->transaction;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transaction->contains($transaction)) {
            $this->transaction[] = $transaction;
            $transaction->setCompteDestinaire($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transaction->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getCompteDestinaire() === $this) {
                $transaction->setCompteDestinaire(null);
            }
        }

        return $this;
    }

    

   

}
