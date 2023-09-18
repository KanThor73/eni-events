<?php

namespace App\Controller;

use App\Entity\Place;
use App\Form\PlaceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/location')]
class LocationController extends AbstractController
{

    #[Route('/createPlace', name: 'createPlace')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $place = new Place();

        $placeForm = $this->createForm(PlaceType::class, $place)->handleRequest($request);

        if($placeForm->isSubmitted() && $placeForm->isValid()){
            $entityManager->persist($place);
            $entityManager->flush();
            $this->addFlash('success', 'Nouveau lieu ajoute');
            $this->redirectToRoute('create_event');
        }

        return $this->render('location/createPlace.html.twig', [
            'form' => $placeForm->createView(),
        ]);
    }
}
