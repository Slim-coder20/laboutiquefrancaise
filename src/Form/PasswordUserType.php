<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        ->add('actualPassword', PasswordType::class, [
            'label' => 'Votre mot de passe actuel ',
            'attr' => [
                'placeholder' => 'Entrez votre mot de passe actuel'
            ],
            'mapped' => false,
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

        ->add('submit', SubmitType::class, [
            'label' => 'Mettre a jour mon mot de passe',
            'attr' => [
                'class' => 'btn btn-success'
            ]

            ])
        // cette méthode va ajouter un écouteur les informations entrée dans le formulaire de modification du mot de passe // 
        ->addEventListener( FormEvents::SUBMIT, function (FormEvent $event) {

           
            $form = $event->getForm();
            $user = $form->getConfig()->getOptions()['data'];
            $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];
            
            
            // 1- récupérer le mot de passe saisi par l'utillisateur et le comparer au mot de passe dans la BDD 
           
            $isValid= $passwordHasher->IsPasswordValid(
                $user,
                $form->get('actualPassword')->getData()
          
            );
            // 2- je renvoie une erreur si ce n'est pas valide // 
            if(!$isValid){
                $form->get('actualPassword')->addError(new FormError("Votre mot de passe actuel n'est pas conform. Veuillez verifier votre saisie"));
            }


        });
    
     

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null
        ]);
    }
}
