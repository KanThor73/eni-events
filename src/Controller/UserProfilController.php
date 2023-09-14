<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Exception\InvalidArgumentException;


class UserProfilController extends AbstractController
{
    #[Route('/user/profil', name: 'app_user_profil')]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $user = $this->getUser();
        $userForm =  $this->createForm(UserProfilType::class, $user);

        $userForm->handleRequest($request);

        if ($userForm->isSubmitted()&&$userForm->isValid()){
            dd($userForm);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Nouveau participant!');
           //return $this->redirectToRoute('app_show_profil' ['id' => $user->getId()])



        }

        return $this->render('user_profil/userProfil.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }
}
