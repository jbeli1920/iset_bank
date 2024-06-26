<?php

namespace App\Controller;

use App\Entity\DemandeCredit;
use App\Entity\Utilisateur;
use App\Form\CreditForm;
use App\Form\DemandeCreditForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CreditsController extends AbstractController
{
    /**
     * @Route("/credits/voir", name="credits")
    */
public function voirCredit(Request $request): Response
 
{      $DemandeCredit = $this->getDoctrine()
        ->getRepository(DemandeCredit::class)
        ->findAll();

        $DemandeCredit = array_filter($DemandeCredit, function($d) {
            return $d->getStatus() == 0;
        });

        $form = $this->createForm(CreditForm::class);
        $form->handleRequest($request);

        return $this->render("credits/voir-credits.html.twig", [
            'DemandeCredit' =>  $DemandeCredit,
            'form' => $form->createView()
        ]);
    
}
    /**
     *  @Route("/credits/accepter/{id}")
     */
    public function accepter_credits(Request $request, int $id): Response
 
    {       
        $entityManager = $this->getDoctrine()->getManager();
        $DemandeCredit = $entityManager->getRepository(DemandeCredit::class)->find($id);
        $DemandeCredit->setStatus(1);
        $DemandeCredit->setNbrMois(3);
        $DemandeCredit->setMontantMois(3);
        $entityManager->persist($DemandeCredit);
        $entityManager->flush();
        return $this->redirectToRoute('credits');
        
    }


    /**
     *  @Route("/credits/refuser/{id}")
     */
    public function refuser_credits(Request $request, int $id): Response
 
    {       
        $entityManager = $this->getDoctrine()->getManager();
        $DemandeCredit = $entityManager->getRepository(DemandeCredit::class)->find($id);
        $DemandeCredit->setStatus(-1);
        $entityManager->flush();
        return $this->redirectToRoute('credits');
        
    }



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
