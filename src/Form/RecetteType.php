<?php

namespace App\Form;

use App\Entity\Recette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir un nom pour la recette'])
                ]
            ])
            ->add('texte', TextareaType::class, [
                'label' => 'Instructions de préparation',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir les instructions de préparation'])
                ]
            ])
            ->add('duree_totale', IntegerType::class, [
                'label' => 'Durée totale (en minutes)',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir la durée de préparation'])
                ]
            ])
            ->add('nombre_personnes', IntegerType::class, [
                'label' => 'Nombre de personnes',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer le nombre de personnes'])
                ]
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo de la recette',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif'
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}