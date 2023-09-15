<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Exception\InvalidArgumentException;


class UserProfilController extends AbstractController
{


    #[Route('/user', name: 'app_user')]
    public function create(
        Request $request,
	UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response
    {
        $user = $this->getUser();

        $userForm =  $this->createForm(UserProfilType::class, $user);

        $userForm->handleRequest($request);

        if ($userForm->isSubmitted()&&$userForm->isValid()){

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
    #[Route('/profil', name: 'app_user_profil')]
    public function showUserProfile(int $id): Response
    {
        // Récupérez l'utilisateur à partir de la base de données en fonction de l'ID
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur n\'a pas été trouvé.');
        }

        // Ensuite, passez l'utilisateur à la vue pour l'afficher ( vue d'exemple la vue twig est à crée)
        return $this->render('user/show_profile.html.twig', [
            'user' => $user,
        ]);
    }
}
