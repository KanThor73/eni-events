<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Event;
use App\Entity\Place;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom de l\'event :'
            ])
            ->add('beginDate', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'label' => 'Date et heure de l\'event :'
            ])
            ->add('duration', TimeType::class, [
                'input' => 'string',
                'widget' => 'single_text',
                'label' => 'Duree de l\'event :'
            ])
            ->add('limitDate', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'label' => 'Date limite d\'inscription :'
            ])
            ->add('nbMaxInscription', null, [
                'label' => 'Nbre max d\'inscription :'
            ])
            ->add('infoEvent', null, [
                'label' => 'Description et infos :'
            ])
            ->add('campus')
            ->add('place', EntityType::class, [
                    'placeholder' => 'Choisir un lieu',
                    'class' => Place::class,
                    'choice_label' => 'name',
                    'label' => 'Lieu :'
                ]
            );

//        $formModifier = function (FormInterface $form, City $city = null) {
//            $places = (null === $city) ? [] : $city->getPlaces();
//            $form->add('place', EntityType::class, [
//                'class' => Place::class,
//                'choices' => $places,
//                'choice_label' => 'name',
//                'placeholder' => 'Choisir',
//                'label' => 'Lieu :'
//            ]);
//        };
//        $builder
//            ->get('place')->addEventListener(
//                FormEvents::POST_SUBMIT,
//                function (FormEvent $event) use ($formModifier) {
//                    $city = $event->getForm()->getData()->getCity();
//                    $formModifier($event->getForm()->getParent(), $city);
//                });

//            ->add('place')
//            ->add('state')
//            ->add('users')
//            ->add('organizer');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
