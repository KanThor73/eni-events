<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\Place;
use App\Entity\State;
use App\Entity\User;
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


        if ($eventForm->isSubmitted() && $eventForm->isValid()) {

            $cityName = $cityForm->getData()->getName();
            $city = $entityManager->getRepository(City::class)->findOneBy(['name' => $cityName]);

            $placeId = $eventForm->getData()->getPlace()->getId();
            $place = $entityManager->getRepository(Place::class)->find($placeId);

            $user = $entityManager->getRepository(User::class)->find($this->getUser()->getId());

            $state = $entityManager->getRepository(State::class)->find(1);

            $event->setPlace($place);
            $event->setCampus($this->getUser()->getCampus());
            $event->setState($state);
            $event->addMember($user);
            $event->setOrganizer($this->getUser());

            $entityManager->persist($event);

            $entityManager->flush();
            $this->addFlash('success', 'Felicitation, vous venez de creer un Eni-Event!');
            return $this->redirectToRoute('event_showroom');
        }

        return $this->render('event/createEvent.html.twig', [
            'eventForm' => $eventForm->createView(),
            'placeForm' => $placeForm->createView(),
            'cityForm' => $cityForm->createView(),
        ]);
    }

    #[Route('/showroom', name: 'event_showroom')]
    public function eventShowroom(Request $request, EntityManagerInterface $entityManager): Response
    {
        $campus = new Campus();
        $campusForm = $this->createForm(CampusType::class, $campus)->handleRequest($request);

        $eventRepo = $entityManager->getRepository(Event::class);
        $events = $eventRepo->findAll();

        return $this->render('event/eventShowroom.html.twig', [
            'campusForm' => $campusForm->createView(),
            'events' => $events
        ]);
    }
}
