<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname',TextType::class,[

            'constraints' => [
                    
                new Length([
                    'min' => 6,
                    'max' => 20
                ])
            
            ],

                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Entrez votre prénom'
                ]
            ])
            ->add('lastname',TextType::class, [
                'constraints' => [
                    
                new Length([
                    'min' => 4,
                    'max' => 20
                ])
            
            ],
                'label' => 'Nom',
                'attr' =>[
                    'placeholder' => 'Entrez votre nom'
                ]
            ])
            
            ->add('email',EmailType::class,[
                'label' => 'Votre adresse email', 
                'attr' => [
                    'placeholder' => 'Saisissez votre email'
                ]
            ])
         
            ->add('plainPassword', RepeatedType::class,[

                'type' => PasswordType::class, 
                'constraints' => [
                    
                new Length([
                    'min' => 6,
                    'max' => 30
                ])
            
            ],
                
                'first_options'  => [
                
                'label' => 'Votre mot de passe ', 
                'attr' => [
                    'placeholder' => 'Entrez votre mot de passe'
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
                'label' => 'inscription', 
                'attr' => [
                    'class' => 'btn btn-success'
                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [
                new UniqueEntity([
                    'entityClass' => User::class,
                    'fields' => 'email'

                ])

            ],
            'data_class' => User::class,
        ]);
    }
}
