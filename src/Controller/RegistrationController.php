<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
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

        if ($form->isSubmitted() && $form->isValid()) {
            $csvFile = $form->get('chargerCSV')->getData();

            if (($handle = fopen($csvFile->getPathname(), 'r')) !== false) {
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    $userCsv = new User();

                    $userCsv->setRoles(["ROLE_USER"]);
                    $userCsv->setPseudo($data[0]);
                    $userCsv->setName($data[1]);
                    $userCsv->setFirstname($data[2]);
                    $userCsv->setEmail($data[3]);
                    $userCsv->setTelephone($data[4]);
                    $userCsv->setPassword($userPasswordHasher->hashPassword(
                        $user,
                        "!ChangeMe!"
                    ));
                    $userCsv->setCampus($campusCsv);

                    $entityManager->persist($userCsv);
                }
                $entityManager->flush();
                fclose($handle);
            }

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

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
