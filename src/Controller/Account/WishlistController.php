<?php

namespace App\Controller\Account;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WishlistController extends AbstractController
{
    #[Route('/compte/liste-de-souhait', name: 'app_account_wishlist')]
    public function index(): Response
    {
        
    return $this->render('account/wishlist/index.html.twig');
     }



  
    #[Route('/compte/liste-de-souhait/add/{id}', name: 'app_account_wishlist_add')]
    public function add(ProductRepository $productRepository, EntityManagerInterface $entityManager,$id,Request $request): Response
    {
        // 1. récupérer l'objet du produit souhaité 
        $product = $productRepository->findOneById($id);
        // 2. Si produit existant . ajouter le produit à la wishlist 
        if($product){
            $this->getUser()->addWishlist($product);
              // 3. Sauvegarder en baese de donnée 
            $entityManager->flush();
        }

        $this ->addFlash(

            'success',
            'Produit correctement ajouté à votre liste de souhait '
        );


        return $this->redirect($request->headers->get('referer'));
        
      
       
    }
    #[Route('/compte/liste-de-souhait/remove/{id}', name: 'app_account_wishlist_remove')]
    public function remove(ProductRepository $productRepository, EntityManagerInterface $entityManager,$id): Response
    {
        // 1. récupérer l'objet du produit à supprimer  
        $product = $productRepository->findOneById($id);
        
        // 2. Si produit existant . supprimer le produit de la liste wishlist 
        if($product){
            $this->addFlash('success', 'Produit correctement supprimé de votre liste de souhait.');
            $this->getUser()->removeWishlist($product);
            $entityManager->flush();
        }else {

            $this->addFlash('danger', 'Produit introuvable ');

        }
        return $this->redirectToRoute('app_account_wishlist');
        // 3. Sauvegarder en baese de donnée 
       
    }

    









}
