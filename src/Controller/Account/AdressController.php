<?php

namespace App\Controller\Account;
use App\Classe\Cart;
use App\Entity\Adress;
use App\Form\AdressUserType;
use App\Repository\AdressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdressController extends AbstractController

{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager; 

    }

        /**
     * création d'une nouvelle route pour l'adresse des utilisateur 
     */

    #[Route('/compte/adresses', name: 'app_account_adresses')]
    public function index(): Response
    {
        
        
     return $this->render('account/address/index.html.twig');
    }
    /**
     * création d'une route qui sert à supprimer une adresse 
     */
    
    
     #[Route('/compte/adresses/delete/{id}', name: 'app_account_adress_delete')]
     public function delete($id, AdressRepository $adressRepository): Response
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
     public function form (Request $request,$id, AdressRepository $adressRepository, Cart $cart): Response
     {   
         if($id){
             $adress = $adressRepository->findOneBy(['id' => $id]);
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
                 "Votre adresse est correctement sauvegarder" );
            
                if($cart->fullQuantity() > 0){
                       return $this->redirectToRoute('app_order');
                       
                }
                return $this->redirectToRoute('app_a