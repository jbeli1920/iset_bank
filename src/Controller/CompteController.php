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
    $entityManager->persist($compteBancaire);
    $entityManager->flush();

    return new Response("haha");
}


    /**
     * @Route("/compte/modifier/{id}")
     */
    public function modifier_compte(int $id): Response
    {
        
        return $this->render('compte/index.html.twig', [
            'controller_name' => 'CompteController',
        ]);
    }


     /**
     * @Route("/compte/supprimer/{id}")
     */
    public function supprimer_compte(int $id): Response
    {
        return $this->render('compte/index.html.twig', [
            'controller_name' => 'CompteController',
        ]);
    }
    /**
     * @Route("/compte/affiche")
     */
    public function affiche_les_compte()

    {
        
        $utilisateur = $this->getDoctrine()
        ->getRepository(Utilisateur::class)
        ->findAll();
        return $this->render("compte/affiche.html.twig", [
            'utilisateur' =>  $utilisateur
        ]);

    }
     /**
     * @Route("/compte/voir_demande_compte")
     */
    public function voir_demande_compte()
    {
        $utilisateur = $this->getDoctrine()
        ->getRepository(Utilisateur::class)
        ->findAll();
        return $this->render('compte/voir-demande-compte.html.twig',[
            'utilisateur' =>  $utilisateur
        ]);

    }


}
