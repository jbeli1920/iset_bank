<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Entity\Utilisateur;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/acceder", name="acceder_profile")
     */
    public function acceder_profile(SessionInterface $session): Response
    {

        if (!$session->has('user')) return $this->redirectToRoute('login');

        $user = $session->get('user');

        $utilisateur = $this->getDoctrine()
            ->getRepository(Utilisateur::class)
            ->find($user['id_utilisateur']);

        $compte = $utilisateur->getCompteBancaire();


        $transactions_envoye = $this->getDoctrine()
            ->getRepository(Transaction::class)
            ->findBy(array('destinaire' => $compte->getNumeroCarte()));

        $transactions_recu = $this->getDoctrine()
            ->getRepository(Transaction::class)
            ->findBy(array('destinataire' => $compte->getNumeroCarte()));

        $tous_transactions = array_merge($transactions_envoye, $transactions_recu);
        return $this->render('profile/afficher-profile.html.twig', [
            'numero_carte' => $compte->getNumeroCarte(),
            'solde' => $compte->getSolde(),
            'code_securite' => $compte->getCodeSecurite(),
            'nom' => $utilisateur->getNom(),
            'prenom' => $utilisateur->getPrenom(),
            'email' => $utilisateur->getEmail(),
            'transactions' => $this->sortByDate($tous_transactions)
        ]);
        
    }


    function sortByDate($array) {
        usort($array, function($a, $b) {
            // Convert the date strings to DateTime objects for comparison
            $dateA = DateTime::createFromFormat('d-m-Y H:i', $a->getDate());
            $dateB = DateTime::createFromFormat('d-m-Y H:i', $b->getDate());

            // Compare the dates in descending order
            return $dateB <=> $dateA;
        });
        return $array;
    }
       /**
     * @Route("/compte/statistique")
     */
public function voirCredit(): Response
 
{     
    return $this->render("compte/satistique.html.twig");
    
}
    

}

