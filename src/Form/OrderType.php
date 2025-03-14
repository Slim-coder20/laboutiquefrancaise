<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Carrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   
        $builder
            ->add('addresses', EntityType::class, [
                'label' => 'Choisissez votre adresse de livraison ', 
                'required' => true, 
                'class' => Adress::class,
                'expanded' => true,
                'choices'  => $options['adresses'],
                'label_html' => true


            ])
       
            ->add('carriers', EntityType::class, [
                'label' => 'Choisissez votre transporteur ', 
                'required' => true, 
                'class' => Carrier::class,
                'expanded' => true,
                'label_html' => true


            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Valider votre commande',
                'attr' => [
                    'class' => 'w-100 btn btn-success'
                ]
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'adresses' => null
        ]);
    }
}
