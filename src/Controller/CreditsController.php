<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreditsController extends AbstractController
{
    /**
     * @Route("/credits/mes-credits")
     */
    public function acceder_profile(): Response
    {
        return $this->render('credits/credits.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
      /**
     * @Route("/credits/voir")
     */
    public function voir_credits(): Response
    {
        return $this->render('credits/voir-credits.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
}
