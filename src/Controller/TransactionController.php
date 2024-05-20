<?php

namespace App\Controller;

use App\Entity\CompteBancaire;
use App\Entity\Transaction;
use App\Entity\Utilisateur;
use App\Form\TransactionForm;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

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

        $mon_compte = $this->getDoctrine()
            ->getRepository(Utilisateur::class)
            ->find($user['id_utilisateur']);

        $mon_compte = $mon_compte->getCompteBancaire();

        $transactions_envoye = $this->getDoctrine()
            ->getRepository(Transaction::class)
            ->findBy(array('destinaire' => $mon_compte->getNumeroCarte()));

        $transactions_recu = $this->getDoctrine()
            ->getRepository(Transaction::class)
            ->findBy(array('destinataire' => $mon_compte->getNumeroCarte()));
        
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
            
            if ($data['montant'] >$user['solde']) return $this->render('transaction/mes-transactions.html.twig', [
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
            $destinataire->setSolde( $destinataire->getSolde() + $data['montant'] );
            $destinaire = $compte->getCompteBancaire();
            $destinaire->setSolde($destinaire->getSolde() - $data['montant'] );
          
            $transaction = new Transaction();
            $transaction->setDestinataire($data['destinataire']);
            $transaction->setDate(date('d-m-Y H:i'));
            $transaction->setMontant($data['montant']);
            $transaction->setNomDestinataire($destinataire->getIdCompte()->getNom());
            $transaction->setNomDestinaire($destinaire->getIdCompte()->getNom());
            $transaction->setDestinaire($destinaire->getNumeroCarte());
            $transaction->setCompteDestinaire($destinaire);
            $entityManager->persist($transaction);
            $entityManager->flush();
            if ($transaction->getDestinaire() == $mon_compte->getNumeroCarte()) $transactions_envoye[] = $transaction;
            else $transactions_recu[] = $transaction;
            // success message
            return $this->render('transaction/mes-transactions.html.twig', [
                'form' => $form->createView(),
                'transactions' => $this->sortByDate(array_merge($transactions_envoye, $transactions_recu)),
                'code_carte' => $mon_compte->getNumeroCarte()
            ]);
        }

        return $this->render('transaction/mes-transactions.html.twig', [
            'form' => $form->createView(),
            'transactions' => $this->sortByDate(array_merge($transactions_envoye, $transactions_recu)),
            'code_carte' => $mon_compte->getNumeroCarte()
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



}
