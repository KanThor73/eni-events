<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\FilterEvent;
use App\Entity\Place;
use App\Entity\State;
use App\Entity\User;
use App\Form\CampusType;
use App\Form\DataLocationType;
use App\Form\EventType;
use App\Form\FilterEventType;
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

        $event = new Event();
        $place = new Place();

        $eventForm = $this->createForm(EventType::class, $event)->handleRequest($request);
        $dataLocationForm = $this->createForm(DataLocationType::class, $place)->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {

            $placeName = $dataLocationForm->getData()->getName();
            $place = $entityManager->getRepository(Place::class)->findOneBy(['name' => $placeName]);
            $user = $entityManager->getRepository(User::class)->find($this->getUser()->getId());

            $state = null;
            if (isset($_POST['create'])) {
                $state = $entityManager->getRepository(State::class)->find(1);
            } elseif (isset($_POST['publish'])) {
                $state = $entityManager->getRepository(State::class)->find(2);
            }

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
            'locationForm' => $dataLocationForm->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Event $event, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (isset($_POST['delete'])) {
            $entityManager->remove($event);
            $entityManager->flush();
            $this->addFlash('error', $event->getName().' ' . 'vient d\'etre supprime');
            return $this->redirectToRoute('event_showroom');
        }

        $place = new Place();
        $defaultCity = $entityManager->getRepository(City::class)->find($event->getPlace()->getCity()->getId());

        $defaultPlace = $entityManager->getRepository(Place::class)->find($event->getPlace()->getId());

        $eventForm = $this->createForm(EventType::class, $event)->handleRequest($request);
        $dataLocationForm = $this->createForm(DataLocationType::class, $place, ['default_city' => $defaultCity, 'default_place' => $defaultPlace])->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $placeName = $dataLocationForm->getData()->getName();
            $place = $entityManager->getRepository(Place::class)->findOneBy(['name' => $placeName]);
            $user = $entityManager->getRepository(User::class)->find($this->getUser()->getId());

            $state = null;
            if (isset($_POST['create'])) {
                $state = $entityManager->getRepository(State::class)->find(1);
                $this->addFlash('success', 'Pour finaliser votre event vous devez le publier');
            } elseif (isset($_POST['publish'])) {
                $state = $entityManager->getRepository(State::class)->find(2);
                $this->addFlash('success', 'Vous venez de publier' . ' ' . $event->getName());
            }

            $event->setPlace($place);
            $event->setCampus($this->getUser()->getCampus());
            $event->setState($state);
            $event->addMember($user);
            $event->setOrganizer($this->getUser());

            $entityManager->flush();
            $this->addFlash('success', 'Modifications sur ' . ' ' . $event->getName() . ' ' . 'effectuees avec succès');
            return $this->redirectToRoute('event_showroom');
        }

        return $this->render('event/editEvent.html.twig', [
            'eventForm' => $eventForm->createView(),
            'locationForm' => $dataLocationForm->createView(),
            'event' => $event
        ]);
    }

    #[Route('/showroom', name: 'event_showroom')]
    public function eventShowroom(Request $request, EntityManagerInterface $entityManager): Response
    {
        $filterEvent = new FilterEvent();
        $researchForm= $this->createForm(FilterEventType::class, $filterEvent);

        $eventRepo = $entityManager->getRepository(Event::class);
        $events = $eventRepo->findAll();

        $researchForm->handleRequest($request);
        if($researchForm->isSubmitted() && $researchForm->isValid()){

        }

        return $this->render('event/eventShowroom.html.twig', [
            'form' => $researchForm->createView(),
            'events' => $events
        ]);
    }

    #[Route('/publish/{id}', name: 'publish')]
    public function publish(Event $event, EntityManagerInterface $entityManager): Response
    {
        $openSate = $entityManager->getRepository(State::class)->find(2);
        $event->setState($openSate);
        $entityManager->flush();
        $this->addFlash('success', $event->getName() . ' ' . 'vient d\'etre ouvert aux inscritions');
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

    #[Route('/cancel/{id}', name: 'cancel')]
    public function cancel(Event $event): Response
    {
        return $this->render('event/cancelEvent.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/stash/{id}', name: 'stash')]
    public function stash(Event $event, Request $request, EntityManagerInterface $entityManager): Response
    {
        $closeSate = $entityManager->getRepository(State::class)->find(3);
        $motif = $request->get('motif');

        $event->setInfoEvent('Motif d\'annulation de l\'event:' . PHP_EOL . $motif);
        $event->setState($closeSate);
        $entityManager->flush();

        return $this->redirectToRoute('event_showroom');
    }

}
