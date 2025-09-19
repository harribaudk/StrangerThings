<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController {

    #[Route('/', name: 'app_welcome', methods: ['GET'])]
    public function welcome(): Response {
        return $this->render('welcome/welcome.html.twig', ['title' => 'Welcome To Hawkins']);
    }

    #[Route('/willestmort', name: 'app_chambre', methods: ['GET'])]
    public function chambre(): Response {
        return $this->render('chambre/chambre.html.twig', ['title' => 'Chambre de Will']);
    }
}