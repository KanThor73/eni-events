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
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

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
                'label' => 'Date et heure de l\'event :',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => new \DateTime('today'), // Date actuelle
                        'message' => 'La date ne peut pas être antérieure à aujourd\'hui.',
                    ]),
                ],
            ])
            ->add('duration', TimeType::class, [
                'input' => 'string',
                'widget' => 'single_text',
                'label' => 'Duree de l\'event :',
            ])
            ->add('limitDate', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'label' => 'Date limite d\'inscription :',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => new \DateTime('today'), // Date actuelle
                        'message' => 'La date ne peut pas être antérieure à aujourd\'hui.',
                    ]),
                ],
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

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
