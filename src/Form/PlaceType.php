<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Place;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => 'Nom du lieu :'])
            ->add('city', EntityType::class, [
                'placeholder' => 'Choisir une ville',
                'class' => City::class,
                'choice_label' => 'name',
                'label' => 'Ville :'
            ])
            ->add('street',null,['label' => 'Rue :'])
            ->add('latitude',null,['label' => 'Latitude :'])
            ->add('longitude',null,['label' => 'Longitude :'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}
