<?php

namespace App\Controller;

use App\Entity\CompteBancaire;
use App\Entity\Reclamation;
use App\Entity\Utilisateur;
use App\Form\ReclamationForm;
use App\Form\ReponseReclamation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class ReclamationController extends AbstractController
{
     /**
     * @Route("/reclamation/voir", name="reclamations")
     */
    public function voir_reclamation_admin(Request $request, MailerInterface $mailer): Response
{
    // Récupérer toutes les réclamations
    $reclamations = $this->getDoctrine()
        ->getRepository(Reclamation::class)
        ->findAll();

    $reclamations = array_filter($reclamations, function(Reclamation $r) {
        return $r->getStatus() == 0;
    });

    $form = $this->createForm(ReponseReclamation::class);
    $form->handleRequest($request);
    
    if ($form->isSubmitted()) {

        $data = $form->getData();

        $template = $this->renderView('reclamation/reponse.html.twig', [
            'reponse' => $data['reponse']
        ]);

        $mailer->send($this->create_response_email($data['email'], $template));

    }

    return $this->render("reclamation/voir-reclamation.html.twig", [
        'Reclamation' => $reclamations,
        'form' => $form->createView()
    ]);
}


    /**
     * @Route("/reclamation/repondre/{id}")
     */
    public function repondre_reclamation(int $id, Request $request, MailerInterface $mailer) {

         $reclamation = $this->getDoctrine()
        ->getRepository(Reclamation::class)
        ->find($id);

        $form = $this->createForm(ReponseReclamation::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();

            $template = $this->renderView('reclamation/reponse.html.twig', [
                'reponse' => $data['reponse'],
                'titre' => $reclamation->getTitre(),
                'description' => $reclamation->getDescription()
            ]);

            $mailer->send($this->create_response_email($data['email'], $template));

            $entityManager = $this->getDoctrine()->getManager();

            $reclamation->setStatus(1);

            $entityManager->flush();

            return $this->redirectToRoute('reclamations');

        }

        return $this->render('reclamation/repondre.html.twig', [
            'form' => $form->createView()
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


 



    private static function create_response_email(string $email, $template) {

            $email = (new Email())
            ->from("no-reply@demomailtrap.com")
            ->to($email)
            ->subject('Reponse a votre reclamation')
            ->html($template);
            return $email;
        }


}
