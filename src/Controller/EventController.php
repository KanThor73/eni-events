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
use App\Form\DataLocationType;
use App\Form\EventType;
use App\Form\PlaceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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

        $event = new Event();
        $place = new Place();
        $city = new City();

        $eventForm = $this->createForm(EventType::class, $event)->handleRequest($request);
        $placeForm = $this->createForm(PlaceType::class, $place)->handleRequest($request);
        $dataLocationForm = $this->createForm(DataLocationType::class, $place)->handleRequest($request);

        $cities = $entityManager->getRepository(City::class)->findAll();


        if ($eventForm->isSubmitted() && $eventForm->isValid()) {

            $placeName = $dataLocationForm->getData()->getName();
            $place = $entityManager->getRepository(Place::class)->findOneBy(['name' => $placeName]);

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
            'locationForm' => $dataLocationForm->createView()
//            'cities' => $cities
//            'cityForm' => $cityForm->createView(),
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

    #[Route('/publish/{id}', name: 'publish')]
    public function publish(Event $event, EntityManagerInterface $entityManager): Response
    {
        $openSate = $entityManager->getRepository(State::class)->find(2);
        $event->setState($openSate);
        $entityManager->flush();
        $this->addFlash('success', $event->getName() . '' . 'vient d\'etre ouvert aux inscritions');
        return $this->redirectToRoute('event_showroom');
    }

    #[Route('/register/{id}', name: 'register')]
    public function register(Event $event, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $event->addMember($user);
        $entityManager->flush();
        $this->addFlash('success', 'Vous venez de vous inscrire a l\'evenement:' . ' ' . $event->getName());
        return $this->redirectToRoute('event_showroom');
    }

    #[Route('/desist/{id}', name: 'desist')]
    public function desist(Event $event, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $event->removeMember($user);
        $entityManager->flush();
        $this->addFlash('success', 'Vous venez de vous desister de' . ' ' . $event->getName());
        return $this->redirectToRoute('event_showroom');
    }

    #[Route('/show/{id}', name: 'show')]
    public function show(Event $event): Response
    {
        return $this->render('event/eventShow.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Event $event): Response
    {
        return $this->render('event/cancelEvent.html.twig', [
            'event' => $event,
        ]);
    }
}
