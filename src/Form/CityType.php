<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Place;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', EntityType::class,[
                'class' => City::class,
                'choice_label' => 'name',
                'label' => 'Ville :'
            ])
            ->add('postCode', null, [
                'label' => 'Code postale :'
            ])
//            ->add('places', EntityType::class,[
//                'class' => Place::class,
//                'choice_label' => 'name',
//                'multiple' => true,
//                'label' => 'Lieu :'
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => City::class,
        ]);
    }
}
