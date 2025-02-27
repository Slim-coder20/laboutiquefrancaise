<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname',TextType::class,[
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Entrez votre prénom'
                ]
            ])
            ->add('lastname',TextType::class, [
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
            ->add('password',PasswordType::class, [
                'label' => 'Votre mot de passe',
                'attr' => [
                    'placeholder' => 'Saisissez votre mot de passe'
                ]
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
            'data_class' => User::class,
        ]);
    }
}
