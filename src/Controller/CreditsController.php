<?php

namespace App\Controller;

use App\Entity\DemandeCredit;
use App\Entity\Utilisateur;
use App\Form\DemandeCreditForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CreditsController extends AbstractController
{
    /**
     * @Route("/credits/mes-credits")
     */
    public function acceder_profile(Request $request, SessionInterface $session): Response
    {

        if (!$session->has('user')) return $this->redirectToRoute('login');

        $user = $session->get('user');
        
        $mon_compte = $this->getDoctrine()
            ->getRepository(Utilisateur::class)
            ->find($user['id_utilisateur']);

        $mon_compte = $mon_compte->getCompteBancaire();

        $demandes = $mon_compte->getDemandeCredits();


        $form = $this->createForm(DemandeCreditForm::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $demande = new DemandeCredit();
            $demande->setMontant($data['montant']);
            $demande->setRaison($data['raison']);
            $demande->setDate(date('d-m-Y'));
            $demande->setCompte($mon_compte);
            $demande->setNbrMois(0);
            $demande->setMontantMois(0);
            $demande->setStatus(0);

            $entityManager->persist($demande);
            $entityManager->flush();
            $demandes[] = $demande;
             return $this->render('credits/credits.html.twig', [
                'demandes' => $demandes,
                'form' => $form->createView()
            ]);
            
        }

        return $this->render('credits/credits.html.twig', [
            'demandes' => $demandes,
            'form' => $form->createView()
        ]);
    }
    
}
