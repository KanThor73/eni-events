<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class CSVType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('chargerCSV', FileType::class, [
                'constraints' => [
                    new File([
                        'maxSize' => '20k',
                        'maxSizeMessage' => 'Votre fichier {{ name }} fait {{ size }} {{ suffix }} / {{ limit }} {{ suffix }} autorisé',
                        'mimeTypes' => [
                            'text/csv'
                        ],
                        'mimeTypesMessage' => 'Formats autorises : {{ types }}',
                    ])
                ],
                'required' => false,
                'label' => 'Fichier .csv :'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
