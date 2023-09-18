<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Place;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DataLocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $defaultCity = $options['default_city'];
        $defaultPlace = $options['default_place'];

        $builder
            ->add('city', EntityType::class, [
                'placeholder' => 'Choisir une ville',
                'class' => City::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => 'Ville :',
                'data' => $defaultCity
            ])
            ->add('name', EntityType::class, [
                'placeholder' => 'Choisir un lieu',
                'class' => Place::class,
                'choice_label' => 'name',
                'label' => 'Lieu :',
                'data' => $defaultPlace
            ])
            ->add('postCode', TextType::class, [
                'mapped' => false,
                'label' => 'Code postale :'
            ])
            ->add('street', TextType::class, ['label' => 'Rue :'])
            ->add('latitude', TextType::class, ['label' => 'Latitude :'])
            ->add('longitude', TextType::class, ['label' => 'Longitude :']);

        $formModifier = function (FormInterface $form, City $city = null) {
            $places = (null === $city) ? [] : $city->getPlaces();
            $firstPlace = $places[0]->getStreet();
//            $postCode = (null === $city) ? [] : $city->getPostCode();

            $form
                ->add('name', EntityType::class, [
                    'class' => Place::class,
                    'choices' => $places,
                    'choice_label' => 'name',
                    'placeholder' => 'Choisir un lieu',
                    'label' => 'Lieu :'
                ])
                ->add('street', TextType::class, [
                    'mapped' => true,
                    'label' => 'Rue :',
                    'data' => $firstPlace
                ]);
        };

        $builder
            ->get('city')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    $city = $event->getForm()->getData();
                    $formModifier($event->getForm()->getParent(), $city);
                });
    }


//                ->add('postCode', TextType::class, [
//                    'mapped' => false,
//                    'data' => $postCode,
//                    'label' => 'Code postale :'
//                ]);

//        street / latitude / longitude

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
            'default_city' => null,
            'default_place' => null
        ]);
    }

//        $formModifier = function (FormInterface $form, Place $place = null) {
//            $street = (null === $place) ? [] : $place->getStreet();
//            $latitude = (null === $place) ? [] : $place->getLatitude();
//            $longitude = (null === $place) ? [] : $place->getLongitude();
//            $form
//                ->add('street', TextType::class, [
//                    'label' => 'Rue :',
//                    'data' => $street
//                ])
//                ->add('latitude', TextType::class, [
//                    'label' => 'Latitude :',
//                    'data' => $latitude
//                ])
//                ->add('longitude', TextType::class, [
//                    'label' => 'Longitude :',
//                    'data' => $longitude
//                ]);
//        };
//
//        $builder
//            ->get('name')->addEventListener(
//                FormEvents::POST_SUBMIT,
//                function (FormEvent $event) use ($formModifier) {
//                    $place = $event->getForm()->getData();
//                    echo($place);
//                    $formModifier($event->getForm()->getParent(), $place);
//                });
}
