<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => "Nom d'utilisateur :", 'attr' => ['value' => 'John Doe']])
            ->add('email', null, [
                'label' => 'Adresse mail :',
                'constraints' => [
                    new Email(['message' => "L'adresse mail n'est pas valide."])
                ],
                'attr' => ['value' => 'fb.lubre@free.fr']
            ])
            ->add('plainPassword', PasswordType::class, [
                                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'value' => '1234567'
                ],
                'label' => 'Mot de passe',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('delivery_address', null, ['label' => 'Adresse de livraison', 'attr' => ['value' => 'Paris']])
            ->add('confirm_password', PasswordType::class, [
                'mapped' => false,
                'label' => 'Confirmer le mot de passe :',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez confirmer votre mot de passe.']),
                ],
                'attr' => ['value' => '1234567']
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
