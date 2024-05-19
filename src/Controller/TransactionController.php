<?php

namespace App\Controller;

use App\Entity\CompteBancaire;
use App\Entity\Transaction;
use App\Entity\Utilisateur;
use App\Form\TransactionForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class TransactionController extends AbstractController
{
    


    /**
     * @Route("/transaction/historique")
     */
    public function afficher_historique_admin(): Response
    {
        return $this->render('transaction/historique_transaction.html.twig', [
            'controller_name' => 'TransactionController',
        ]);
    }

    /**
     * @Route("/transaction/mes-transactions")
     */
    public function afficher_historique_client(Request $request, SessionInterface $session): Response
    {

        if (!$session->has('user')) return $this->redirectToRoute('login');

        $user = $session->get('user');

        
         $form = $this->createForm(TransactionForm::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $destinataire = $this->getDoctrine()
            ->getRepository(CompteBancaire::class)
            ->findOneBy(array('numero_carte'=> $data['destinataire']));
            if (!$destinataire) return $this->render('transaction/mes-transactions.html.twig', [
                'form' => $form->createView(),
                'erreur_destinataire' => "Destinataire n'est pas trouvé"
            ]);
            
            if ($data['montant'] > $user['solde']) return $this->render('transaction/mes-transactions.html.twig', [
                'form' => $form->createView(),
                'erreur_solde' => 'Votre solde est insuffisant'
            ]);

            $entityManager = $this->getDoctrine()->getManager();
            
            // check if password is correct
            $compte = $this->getDoctrine()
            ->getRepository(Utilisateur::class)
            ->find($user['id_utilisateur']);



            if ($compte->getMotDePasse() != $data['mot_de_passe']) return $this->render('transaction/mes-transactions.html.twig', [
                'form' => $form->createView(),
                'erreur_password' => 'Mot de passe est incorrect'
            ]);
            // make the transaction
            // success message
            return new Response($user['solde']);
        }

        return $this->render('transaction/mes-transactions.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
