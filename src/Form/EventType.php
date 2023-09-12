<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
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
//            ->add('place')
//            ->add('state')
//            ->add('users')
            ->add('campus', TextType::class, ['label' => 'Campus :']);
//            ->add('organizer');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
