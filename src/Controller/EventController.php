<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\Place;
use App\Form\CampusType;
use App\Form\CityType;
use App\Form\EventType;
use App\Form\PlaceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/create', name: 'create_event')]
    public function createEvent(Request $request, EntityManagerInterface $entityManager): Response
    {
        //recupere le campus la rue et le code postal quand utilisateur connecte pour les injecter dans la vue
//        $user = $this->getUser();
//        $campus = $user->getCampus();

        $event = new Event();
        $place = new Place();
        $city = new City();

        $eventForm = $this->createForm(EventType::class, $event)->handleRequest($request);
        $placeForm = $this->createForm(PlaceType::class, $place)->handleRequest($request);
        $cityForm = $this->createForm(CityType::class, $city)->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid() && $placeForm->isSubmitted() && $placeForm->isValid() && $cityForm->isSubmitted() && $cityForm->isValid()) {




            $entityManager->persist($place);
            $entityManager->persist($city);
            $entityManager->persist($event);

            $entityManager->flush();
            $this->addFlash('success', 'Felicitation, vous venez de creer un Eni-Event!');
            return $this->redirectToRoute('home');
        }

        return $this->render('event/createEvent.html.twig', [
            'eventForm' => $eventForm->createView(),
            'placeForm' => $placeForm->createView(),
            'cityForm' => $cityForm->createView(),
            'codePostale' => '73000'
        ]);
    }

    #[Route('/showroom', name: 'event_showroom')]
    public function eventShowroom(Request $request): Response
    {
        $campus = new Campus();

        $campusForm = $this->createForm(CampusType::class, $campus)->handleRequest($request);



        return $this->render('event/eventShowroom.html.twig', [
            'campusForm' => $campusForm->createView(),
        ]);
    }
}
