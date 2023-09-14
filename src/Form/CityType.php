<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Place;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('name')
            ->add('name', EntityType::class, [
                'placeholder' => 'Choisir une ville',
                'class' => City::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => 'Ville :'
            ])
            ->add('postCode', null, [
                'label' => 'Code postale :'
            ])
            ->add('places');

//
//        ->add('places', EntityType::class, [
//            'class' => Place::class,
//            'choice_label' => 'name',
//            'placeholder' => 'Choisir un lieu',
//            'label' => 'Lieu :'
//        ]);

//        $formModifier = function (FormInterface $form, City $city = null) {
//            $places = (null === $city) ? [] : $city->getPlaces();
//            $form->add('name', EntityType::class, [
//                'class' => Place::class,
//                'choices' => $places,
//                'choice_label' => 'name',
//                'placeholder' => 'Choisir un lieu',
//                'label' => 'Lieu :'
//            ]);
//        };
//
//        $builder->get('name')->addEventListener(
//            FormEvents::POST_SUBMIT,
//            function (FormEvent $event) use ($formModifier) {
//                $city = $event->getForm()->getData();
//                $formModifier($event->getForm()->getParent(), $city);
//            }
//        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => City::class,
        ]);
    }
}
