<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        $session->invalidate();
        return $this->render('chambre/chambre.html.twig');
    }

    #[Route('/gate/logout', name: 'app_gate_logout', methods: ['GET'])]
    public function logout(SessionInterface $session): Response
    {
        $session->remove('gate_unlocked');
        return $this->redirectToRoute('app_welcome');
    }

    #[Route('/inverse', name: 'app_inverse', methods: ['GET'])]
    public function inverse(SessionInterface $session): Response
    {
        return $this->render('inverse/inverse.html.twig');
    }

    #[Route('/inverse/data', name: 'app_data')]
    public function data(): JsonResponse
    {
        return $this->json([
            "project" => "Stranger Things",
            "status" => "classified",
            "experiments" => [
                ["id" => 1, "subject" => "011", "result" => "success"],
                ["id" => 2, "subject" => "008", "result" => "unknown"]
            ],
            "notes" => "Accès restreint au personnel autorisé uniquement.",
            "hidden" => [
                "encrypted" => "ZmV0ZWZvcmFpbmU="
            ]
        ]);
    }

    #[Route('/inverse/submit', name: 'app_submit', methods: ['POST'])]
    public function submitFlag(Request $request): Response
    {
        $flag = $request->request->get('flag');
        $session = $request->getSession();

        if ($flag === 'feteforaine') {
            $session->set('ctf_completed', true);
            return $this->redirectToRoute('fete_page');
        }

        $this->addFlash('error', 'Flag incorrect, essaie encore !');
        return $this->redirectToRoute('app_inverse');
    }

    #[Route('/fete', name: 'fete_page')]
    public function fete(Request $request): Response
    {
        $session = $request->getSession();

        if (!$session->get('ctf_completed', false)) {
            return $this->redirectToRoute('app_inverse');
        }
        $session->invalidate();

        return $this->render('fete/fete.html.twig');
    }

    #[Route('/terminal', name: 'terminal_page')]
    public function terminal(): Response
    {

        return $this->render('terminal/terminal.html.twig');
    }

    #[Route('/laboratoire', name: 'app_laboratoire', methods: ['GET'])]
    public function laboratoire(): Response
    {
        return $this->render('laboratoire/laboratoire.html.twig');
    }

    #[Route('/final', name: 'app_final', methods: ['GET'])]
    public function final(): Response
    {
        return $this->render('final/final.html.twig');
    }




}