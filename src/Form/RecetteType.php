<?php
namespace App\Form;

use App\Entity\Recette;
use App\Entity\Ingredient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
                'label' => 'Nom de la recette',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir un nom pour la recette']),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de la recette',
                ],
            ])
            ->add('texte', TextareaType::class, [
                'label' => 'Instructions de préparation',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir les instructions de préparation']),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Expliquer la préparation',
                    'rows' => 5,
                ],
            ])
            ->add('duree_totale', IntegerType::class, [
                'label' => 'Durée totale (en minutes)',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir la durée de préparation']),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Durée en minutes',
                ],
            ])
            ->add('nombre_personnes', IntegerType::class, [
                'label' => 'Nombre de personnes',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez indiquer le nombre de personnes']),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nombre de personnes',
                ],
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo de la recette',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control-file',
                ],
            ])
            ->add('ingredients', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Ingrédients',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}