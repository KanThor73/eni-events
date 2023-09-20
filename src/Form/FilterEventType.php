<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\FilterEvent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'placeholder' => 'Choisir un campus',
                'choice_label' => 'name',
                'required' => false
            ])
            ->add('eventName', TextType::class, [
                'label' => 'Le nom de la sortie contient',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Search'
                )
            ])
            ->add('beginDate', DateType::class, [
                'input' => 'datetime_immutable',
                'label' => 'Entre le',
                'required' => false,
            ])
            ->add('endDate', DateType::class, [
                'input' => 'datetime_immutable',
                'label' => 'Et le',
                'required' => false,
            ])
            ->add('isOrganizer', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false,
            ])
            ->add('isMember', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'required' => false,
            ])
            ->add('isNotMember', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false,
            ])
            ->add('passed', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FilterEvent::class,
        ]);
    }
}
