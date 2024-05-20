<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 */
class Utilisateur
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    
    /**
     * @ORM\Column(type="string", length=255)
     */

    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code_confirmation_email;

    /**
     * @Assert\NotBlank
    */
    /**
     * @ORM\Column(type="string", length=255)
    */
    
    private $mot_de_passe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $profil;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code_changement_password;

    /**
     * @ORM\Column(type="integer")
     */
    private $email_confirme;

    /**
     * @ORM\OneToOne(targetEntity=CompteBancaire::class, mappedBy="id_compte", cascade={"persist", "remove"})
     */
    private $compteBancaire;


    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function get_code_changement_password(): ?string {
        return $this->code_changement_password;
    }

    public function get_email_confirme(): ?int
    {
        return $this->email_confirme;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function set_code_changement_password(string $code): self {
        $this->code_changement_password = $code;
        
        return $this;
    }

    public function set_email_confirme(int $email_confirme): self
    {
        $this->email_confirme = $email_confirme;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getemail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCodeConfirmationEmail(): ?string
    {
        return $this->code_confirmation_email;
    }

    public function setCodeConfirmationEmail(string $code_confirmation_email): self
    {
        $this->code_confirmation_email = $code_confirmation_email;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->mot_de_passe;
    }

    public function setMotDePasse(string $mot_de_passe): self
    {
        $this->mot_de_passe = $mot_de_passe;

        return $this;
    }

    public function getProfil(): ?string
    {
        return $this->profil;
    }

    public function setProfil(string $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getCompteBancaire(): ?CompteBancaire
    {
        return $this->compteBancaire;
    }

    public function setCompteBancaire(CompteBancaire $compteBancaire): self
    {
        // set the owning side of the relation if necessary
        if ($compteBancaire->getIdCompte() !== $this) {
            $compteBancaire->setIdCompte($this);
        }

        $this->compteBancaire = $compteBancaire;

        return $this;
    }

}
