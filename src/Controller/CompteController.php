<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Utilisateur;
use App\Entity\CompteBancaire;

class CompteController extends AbstractController
{
  /**
 * @Route("/compte/accepter/{id}")
 */
public function accepter_compte(Request $request, int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $compteBancaire = $entityManager->getRepository(CompteBancaire::class)->find($id);
    $compteBancaire->setNumeroCarte( '5555' . sprintf('%012d', mt_rand(100000000000, 999999999999) ) );
    $compteBancaire->setCodeSecurite(rand(1000,9999));
    $compteBancaire->setSolde(50);
    $compteBancaire->setConfirme(1);
    $compteBancaire->setStatus(1);
    $entityManager->persist($compteBancaire);
    $entityManager->flush();

    return $this->redirectToRoute('demandes');
}



/**
 * @Route("/compte/refuser/{id}")
 */
public function refuser_compte(Request $request, int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $compteBancaire = $entityManager->getRepository(CompteBancaire::class)->find($id);
    
    $utilisateur = $compteBancaire->getIdCompte();

    $entityManager->remove($compteBancaire);

    $entityManager->remove($utilisateur);

    $entityManager->flush();

    return $this->redirectToRoute('demandes');
}


   

    /**
     * @Route("/compte/affiche", name="comptes")
     */
    public function affiche_les_compte()

    {
        
        $utilisateur = $this->getDoctrine()
        ->getRepository(Utilisateur::class)
        ->findAll();

        $comptes_confirmes = array_filter($utilisateur, function(Utilisateur $u) {
            $c = $u->getCompteBancaire();
            if (!$c) return false;
            return $c->getConfirme() == 1;
        });

        return $this->render("compte/affiche.html.twig", [
            'utilisateur' =>  $comptes_confirmes
        ]);

    }
     /**
     * @Route("/compte/voir_demande_compte", name="demandes")
     */
    public function voir_demande_compte()
    {
        $utilisateur = $this->getDoctrine()
        ->getRepository(Utilisateur::class)
        ->findAll();

        $comptes_non_confirmes = array_filter($utilisateur, function(Utilisateur $u) {
            $c = $u->getCompteBancaire();
            if (!$c) return false;
            return $c->getConfirme() == 0;
        });


        return $this->render('compte/voir-demande-compte.html.twig',[
            'utilisateur' =>  $comptes_non_confirmes
        ]);

    }

    /**
     * @Route("/compte/details/{id}")
     */
    public function details_compte(int $id) {

        $utilisateur = $this->getDoctrine()
        ->getRepository(Utilisateur::class)
        ->find($id);

        $compte = $utilisateur->getCompteBancaire();


        return $this->render('compte/details.html.twig', [
            'carte' => $compte->getNumeroCarte(),
            'code_securite' => $compte->getCodeSecurite(),
            'solde' => $compte->getSolde(),
            'id' => $id,
            'status' => $compte->getStatus()
        ]);
    }


    /**
     * @Route("/compte/activer/{id}")
    */
    public function activer_compte(int $id) {
        $entityManager = $this->getDoctrine()->getManager();
        $utilisateur = $this->getDoctrine()
        ->getRepository(Utilisateur::class)
        ->find($id);

        $compte = $utilisateur->getCompteBancaire();
        $compte->setStatus(1);
        $entityManager->flush();

        return $this->redirectToRoute('comptes');

    }


      /**
     * @Route("/compte/desactiver/{id}")
    */
    public function desactiver_compte(int $id) {
        $entityManager = $this->getDoctrine()->getManager();
        $utilisateur = $this->getDoctrine()
        ->getRepository(Utilisateur::class)
        ->find($id);

        $compte = $utilisateur->getCompteBancaire();
        $compte->setStatus(0);
        $entityManager->flush();

        return $this->redirectToRoute('comptes');

    }


}
