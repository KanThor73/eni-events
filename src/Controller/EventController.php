<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Event;
use App\Entity\Place;
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

        $eventForm = $this->createForm(EventType::class, $event)->handleRequest($request);
        $placeForm = $this->createForm(PlaceType::class, $place)->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid() && $placeForm->isSubmitted() && $placeForm->isValid()) {

            $entityManager->persist($place);

            $entityManager->persist($event);

            $entityManager->flush();

            $this->addFlash('success', 'Felicitation, vous venez de creer un Eni-Event!');

            return $this->redirectToRoute('home');
        }

        return $this->render('event/createEvent.html.twig', [
            'eventForm' => $eventForm->createView(),
            'placeForm' => $placeForm->createView(),
            'codePostale' => '73000'
        ]);
    }

    #[Route('/showroom', name: 'event_showroom')]
    public function eventShowroom(): Response
    {
        return $this->render('event/eventShowroom.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }
}
