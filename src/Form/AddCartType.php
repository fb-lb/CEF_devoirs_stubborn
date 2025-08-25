<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddCartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('size', ChoiceType::class, [
                'choices' => [
                    'XS' => 'xs',
                    'S' => 's',
                    'M' => 'm',
                    'L' => 'l',
                    'XL' => 'xl'
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Taille',
                'choice_label' => function($choice, $key, $value) {
                    return $key;
                }
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter au panier'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
