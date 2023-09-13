<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowProfilController extends AbstractController
{
    #[Route('/show/profil', name: 'app_show_profil')]
    public function index(): Response
    {
        return $this->render('show_profil/showProfil.html.twig', [
            'controller_name' => 'ShowProfilController',
        ]);
    }
}
