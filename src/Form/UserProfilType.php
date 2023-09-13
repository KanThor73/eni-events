<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Campus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email :',
                'mapped' => false,
            ])
            ->add('password', null, [
                'label' => 'Mot de passe :'
            ])
            ->add('name', null, [
                'label' => 'Nom :'
            ])
            ->add('firstname', null, [
                'label' => 'Prenom :'
            ])
            ->add('telephone', null, [
                'label' => 'Telephone :'
            ])
            ->add('pseudo', null, [
                'label' => 'pseudo :'
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'require'=>false
        ]);
    }
}