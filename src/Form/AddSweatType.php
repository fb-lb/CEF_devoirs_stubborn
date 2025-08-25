<?php

namespace App\Form;

use App\Entity\Sweat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddSweatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
                'mapped' => false,
                'attr' => [
                    'accept' => 'image/jpeg, image/png'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image au format JPEG ou PNG'
                    ])
                ],
                'required' => $options['data']->getFileName() ? false : true,
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner un nom'])
                ]
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner un prix'])
                ]
            ])
            ->add('top', CheckboxType::class, [
                'label' => "Afficher sur la page d'accueil : ",
                'required' => false
            ])
            ->add('sweatVariants', CollectionType::class, [
                'entry_type' => AddSweatVariantType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
            ])
        ;
        
        if ($options['with_add']) {
            $builder->add('add', SubmitType::class, ['label' => 'Ajouter']);
        }
        if ($options['with_update']) {
            $builder->add('update', SubmitType::class, ['label' => 'Modifier']);
        }
        if ($options['with_delete']) {
            $builder->add('delete', SubmitType::class, ['label' => 'Supprimer']);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sweat::class,
            'with_add' => false,
            'with_update' => false,
            'with_delete' => false
        ]);
    }
}
