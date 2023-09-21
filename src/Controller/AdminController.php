<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/manage', name: 'manage')]
    public function manage(UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/manage.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/admin/delete/{id}', name: 'delete')]
    public function delete(User $user, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $users = $userRepository->findAll();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->render('admin/manage.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/admin/disable/{id}', name: 'disable')]
    public function disable(User $user, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user->setRoles(["ROLE_DISABLE"]);
        $entityManager->flush();
        $users = $userRepository->findAll();

        return $this->render('admin/manage.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/admin/enable/{id}', name: 'enable')]
    public function enable(User $user, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $users = $userRepository->findAll();
        $user->setRoles(["ROLE_USER"]);
        $entityManager->flush();
        return $this->render('admin/manage.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/admin/cancel', name: 'cancelEvent')]
    public function cancel(EventRepository $eventRepository, EntityManagerInterface $entityManager): Response
    {
        $events = $eventRepository->findAll();

        return $this->render('admin/cancel.html.twig', [
            'events' => $events
        ]);
    }

}
