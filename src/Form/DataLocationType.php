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
            ->add('street', null, ['label' => 'Rue :'])
            ->add('latitude', null, ['label' => 'Latitude :'])
            ->add('longitude', null, ['label' => 'Longitude :']);

        $formModifier = function (FormInterface $form, City $city = null) {
            $places = (null === $city) ? [] : $city->getPlaces();
            $form->add('name', EntityType::class, [
                'class' => Place::class,
                'choices' => $places,
                'choice_label' => 'name',
                'placeholder' => 'Choisir un lieu',
                'label' => 'Lieu :'
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
            'default_city' => null,
            'default_place' => null
        ]);
    }
}
