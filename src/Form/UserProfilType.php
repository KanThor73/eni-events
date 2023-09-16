<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Campus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;

class UserProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email :',
                'mapped' => false,
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Nouveau mot de passe :',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    #new NotBlank([
                    #    'message' => 'Please enter a password',
                    #]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
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
            ])
            ->add('userPicture', FileType::class, [
                'constraints' => [
                    new Image([
                        'maxSize' => '50k',
                        'maxSizeMessage' => 'Votre fichier {{ name }} fait {{ size }} {{ suffix }} / {{ limit }} {{ suffix }} autorisé',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => 'Formats autorises : {{ types }}',
                    ])
                ],
                'mapped' => false,
                'label' => 'Avatar :'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'require' => false
        ]);
    }
}
