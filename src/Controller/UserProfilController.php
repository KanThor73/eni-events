<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserProfilController extends AbstractController
{
    #[Route('/user/profil', name: 'app_user_profil')]
    public function create(Request $request): Response
    {
        $user = new User();
        $userForm = $this->createForm(UserProfilType::class, $user)->handleRequest($request);

        return $this->render('user_profil/userProfil.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }
}
