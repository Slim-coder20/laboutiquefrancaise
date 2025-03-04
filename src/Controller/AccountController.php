<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Form\AdressUserType;
use App\Form\PasswordUserType;
use App\Repository\AdressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AccountController extends AbstractController
{   
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager; 

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
     * création d'une route qui sert à supprimer une adresse 
     */
    
    
    #[Route('/compte/adresses/delete/{id}', name: 'app_account_adress_delete')]
    public function adressesDelete($id, AdressRepository $adressRepository): Response
    {
        $adress = $adressRepository->findOneBy(['id' => $id]);
        if (!$adress OR $adress->getUser() !=  $this->getUser()){
                
            return $this->redirectToRoute('app_account_adresses');
        }
        $this ->addFlash(

            'success',
            "Votre adresse est correctement supprimé"
        );
        $this->entityManager->remove($adress);
        $this->entityManager->flush();
        return $this->redirectToRoute('app_account_adresses');
    
    }
    
    /**
     * Création d'une nouvelle route qui nous servira à ajouter ou modifier son adresse  
     */
    
    
    #[Route('/compte/adresse/ajouter/{id}', name: 'app_account_adress_form', defaults: ['id' => null])]
    public function adressForm(Request $request,$id, AdressRepository $adressRepository): Response
    {   
        if($id){
            $adress = $adressRepository->findOneBy($id);
            /**
             * On essaie de sécuriser notre id utilisateur 
             */
            if (!$adress OR $adress->getUser() !=  $this->getUser()){
                
                return $this->redirectToRoute('app_account_adresses');
            }
        }
        else{
        $adress = New Adress;
        $adress->setUser($this->getUser());
        }

        
        $form = $this->createForm(AdressUserType::class, $adress);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($adress); 
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
