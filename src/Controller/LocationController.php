<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Place;
use App\Form\CityType;
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
    public function createPlace(EntityManagerInterface $entityManager, Request $request): Response
    {
        $place = new Place();

        $placeForm = $this->createForm(PlaceType::class, $place)->handleRequest($request);

        if($placeForm->isSubmitted() && $placeForm->isValid()){
            $entityManager->persist($place);
            $entityManager->flush();
            $this->addFlash('success', 'Nouveau lieu ajoute');
            return $this->redirectToRoute('create_event');
        }

        return $this->render('location/createPlace.html.twig', [
            'form' => $placeForm->createView(),
        ]);
    }

    #[Route('/createCity.html.twig', name: 'createCity.html.twig')]
    public function createCity(EntityManagerInterface $entityManager, Request $request): Response
    {
        $city = new City();

        $cityForm = $this->createForm(CityType::class, $city)->handleRequest($request);

        if($cityForm->isSubmitted() && $cityForm->isValid()){
            $entityManager->persist($city);
            $entityManager->flush();
            $this->addFlash('success', 'Nouvelle ville ajoutee');
            return $this->redirectToRoute('create_event');
        }

        return $this->render('location/createCity.html.twig', [
            'form' => $cityForm->createView(),
        ]);
    }
}
