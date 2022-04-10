<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParcoursApprenantsController extends AbstractController
{
    #[Route('/parcours/apprenants', name: 'app_parcours_apprenants')]
    public function index(): Response
    {
        return $this->render('parcours_apprenants/index.html.twig', [
            'controller_name' => 'ParcoursApprenantsController',
        ]);
    }
}
