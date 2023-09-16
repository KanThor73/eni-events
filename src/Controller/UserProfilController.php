<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Exception\InvalidArgumentException;


class UserProfilController extends AbstractController
{

    /**
     * @throws Exception
     */
    #[Route('/user/profil', name: 'app_user_profil')]
    public function create(
        Request                     $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface      $entityManager
    ): Response
    {
        $user = $this->getUser();
        $userForm = $this->createForm(UserProfilType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            /** @var UploadedFile $userPicture */
            $userPicture = $userForm->get('userPicture')->getData();
            $fileName = bin2hex(random_bytes(10));
            $extension = $userPicture->guessExtension() ?? 'bin';
            $userPicture->move('userPicture', $fileName .'.' . $extension);

            if ($userForm->get('plainPassword')->getData() != null) { // si la personne saisit un nouveau mot de passe
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $userForm->get('plainPassword')->getData()
                    )
                );
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Nouveau participant!');
            //return $this->redirectToRoute('app_show_profil' ['id' => $user->getId()])
        }

        return $this->render('user_profil/userProfil.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }

    #[Route('/user/showProfil/{id}', name: 'showProfil')]
    function showProfil(User $user): Response
    {
        return $this->render('user_profil/showProfil.html.twig', [
            'user' => $user
        ]);
    }
}
