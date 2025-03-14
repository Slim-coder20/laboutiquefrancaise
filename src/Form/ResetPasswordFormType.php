<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('plainPassword', RepeatedType::class,[

            'type' => PasswordType::class, 
            'constraints' => [
                
            new Length([
                'min' => 6,
                'max' => 30
            ])
        
        ],
            
            'first_options'  => [
            
            'label' => 'Votre nouveau mot de passe ', 
            'attr' => [
                'placeholder' => 'Entrez votre nouveau mot de passe'
            ],
            
            'hash_property_path' => 'password'],
            
            
            'second_options' => 
            [
            'label' => 'Confirmez votre mot de passe',
            'attr' => [
                'placeholder' => 'Confirmez votre mot de passe'
            ]
            ],
           
            'mapped' => false,
        ])
        ->add('submit',SubmitType::class, [
            'label' => 'renitialiser mon mot de passe', 
            'attr' => [
                'class' => 'btn btn-primary'
            ]

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
