<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Form\AdressUserType;
use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AccountController extends AbstractController
{   
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em; 

    }
    
    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        
        
     return $this->render('account/index.html.twig');
    }
    
    // création d'une nouvelle route pour la modification du mot de pasee Utilisateur // 

    #[Route('/compte/modifier-mot-de-passe', name: 'app_account_modify_pwd')]
    public function password(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {   
       

        $user = $this->getUser();

        $form = $this->createForm(PasswordUserType::class, $user, [
            'passwordHasher' => $passwordHasher 
        ]);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           $this->entityManager-> flush();
           $this ->addFlash(

            'success',
            'Votre mot de passe est correctement mis a jour'
        );
        }
     
     
     return $this->render('account/password.html.twig',[
        'modifyPwd' => $form->createView()
     ]);
    }

    /**
     * création d'une nouvelle route pour l'adresse des utilisateur 
     */

    #[Route('/compte/adresses', name: 'app_account_adresses')]
    public function adresses(): Response
    {
        
        
     return $this->render('account/adresses.html.twig');
    }
    /**
     * Création d'une nouvelle route qui nous servira pour notre formulaire d'adresses 
     */
    
    
    #[Route('/compte/adresse/ajouter', name: 'app_account_adress_form')]
    public function adressForm(Request $request): Response
    {   
        $adress = New Adress;
        $adress->setUser($this->getUser());
        
        $form = $this->createForm(AdressUserType::class, $adress);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $this->entiityManager->persist($adress); 
            $this->entityManager->flush(); 
            $this ->addFlash(

                'success',
                "Votre adresse est correctement sauvegarder"
            );
            return $this->redirectToRoute('app_account_adresses');

        }
        
        
     return $this->render('account/adressForm.html.twig', [
        'adressForm' => $form
     ]);
    }










}
