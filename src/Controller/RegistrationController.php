<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\CSVType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/admin/register', name: 'app_register')]
    public function register(CampusRepository $campusRepository, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $campusCsv = $campusRepository->find(1);
        $user = new User();
        $user->setRoles(["ROLE_USER"]);

	$form = $this->createForm(RegistrationFormType::class, $user);
	$form->handleRequest($request);

	$formCSV = $this->createForm(CSVType::class);
	$formCSV->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
	    );

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_register');
	}

	if ($formCSV->isSubmitted() && $formCSV->isValid()) {
            $csvFile = $formCSV->get('chargerCSV')->getData();

	    if (($handle = fopen($csvFile->getPathname(), 'r'))) {
		    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    $userCsv = new User();

                    $userCsv->setRoles(["ROLE_USER"]);
                    $userCsv->setPseudo($data[0]);
                    $userCsv->setName($data[1]);
                    $userCsv->setFirstname($data[2]);
                    $userCsv->setEmail($data[3]);
                    $userCsv->setTelephone($data[4]);
                    $userCsv->setPassword('$2y$13$aOUoLq5GdLeNzdyKApDt5ez4g6b3XtPYmdlnCpK.QIHQZMNoI4bri');
                    $userCsv->setCampus($campusCsv);
                    $entityManager->persist($userCsv);
                }
                $entityManager->flush();
		fclose($handle);

		return $this->redirectToRoute('app_register');
            }
	}

        return $this->render('registration/register.html.twig', [
		'registrationForm' => $form->createView(),
		'csvForm' => $formCSV->createView()
        ]);
    }
}
