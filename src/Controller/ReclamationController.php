<?php

namespace App\Controller;

use App\Entity\CompteBancaire;
use App\Entity\Reclamation;
use App\Entity\Utilisateur;
use App\Form\ReclamationForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;


class ReclamationController extends AbstractController
{
     /**
     * @Route("/reclamation/voir")
     */
    public function voir_reclamation_admin(): Response
{
    // Récupérer toutes les réclamations
    $reclamations = $this->getDoctrine()
        ->getRepository(Reclamation::class)
        ->findAll();


    


    return $this->render("reclamation/voir-reclamation.html.twig", [
        'Reclamation' => $reclamations
    ]);
}

    /**
     * @Route("/reclamation/mes-reclamations")
     */
    public function voir_reclamation_client(SessionInterface $session, Request $request): Response
    {

        if (!$session->has('user')) return $this->redirectToRoute('login');

        $user = $session->get('user');

        $mon_compte = $this->getDoctrine()
            ->getRepository(Utilisateur::class)
            ->find($user['id_utilisateur']);

        $mon_compte = $mon_compte->getCompteBancaire();

        $reclamations = $mon_compte->getReclamations();

        $form = $this->createForm(ReclamationForm::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();

            

            // create the reclamation
            $reclamation = new Reclamation();
            $reclamation->setTitre($data['titre']);
            $reclamation->setDescription($data['description']);
            $reclamation->setDate(date('d-m-Y'));
            $reclamation->setStatus(0);
            $reclamation->setCompte($mon_compte);
            
            $entityManager->persist($reclamation);
            $entityManager->flush();
            $reclamations[] = $reclamation;

            return $this->render('reclamation/mes-reclamations.html.twig', [
            'reclamations' => $reclamations,
            'form' => $form->createView()
        ]);

        }

        return $this->render('reclamation/mes-reclamations.html.twig', [
            'reclamations' => $reclamations,
            'form' => $form->createView()
            
        ]);
    }


     /**
     * @Route("/reclamation/repondre/{id}", name="app_reclamation")
     */
    public function repondre_reclamation(int $id): Response	
    {
        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }


}
