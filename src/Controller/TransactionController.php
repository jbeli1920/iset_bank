<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    /**
     * @Route("/transaction/transfert")
     */
    public function transfert(): Response
    {
        return $this->render('transaction/index.html.twig', [
            'controller_name' => 'TransactionController',
        ]);
    }


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
    public function afficher_historique_client(): Response
    {
        return $this->render('transaction/mes-transactions.html.twig', [
            'controller_name' => 'TransactionController',
        ]);
    }

}
