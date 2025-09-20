<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController {

    #[Route('/', name: 'app_welcome', methods: ['GET', 'POST'])]
    public function wecolme(Request $request, SessionInterface $session): Response
    {
        if ($request->isMethod('POST')) {
            $entered = $request->request->get('password', '');
            $hashed = $this->getParameter('app.gate_password_hash');
            if (password_verify($entered, $hashed)) {
                $session->set('gate_unlocked', true);
                return $this->redirectToRoute('app_chambre');
            } else {
                $this->addFlash('error', 'Mot de passe incorrect');
                return $this->redirectToRoute('app_welcome');
            }
        }
        return $this->render('welcome/welcome.html.twig');
    }

    #[Route('/chambre', name: 'app_chambre', methods: ['GET'])]
    public function chambre(SessionInterface $session): Response
    {
        if (!$session->get('gate_unlocked', false)) {
            return $this->redirectToRoute('app_welcome');
        }
        return $this->render('chambre/chambre.html.twig');
    }

    #[Route('/gate/logout', name: 'app_gate_logout', methods: ['GET'])]
    public function logout(SessionInterface $session): Response
    {
        $session->remove('gate_unlocked');
        return $this->redirectToRoute('app_welcome');
    }
}