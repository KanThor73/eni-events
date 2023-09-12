<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'start')]
    public function index(): Response
    {
        return $this->render('home/userProfil.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/home', name: 'home')]
    public function home(): Response
    {
        return $this->render('home/home.html.twig');
    }
}
