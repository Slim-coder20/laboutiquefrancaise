<?php

namespace App\Controller;


use App\Classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

final class CartController extends AbstractController
{
    #[Route('/mon-panier/{motif}', name: 'app_cart', defaults: ['motif' => null])]
    public function index(Cart $cart, $motif): Response
    {   
        if($motif ==  'annulation'){
            $this->addFlash(
                'info',
                'Paiment annulé: Vous pouvez mettre a jour votre panier et votre commande.' 
            );
        }

        return $this->render('cart/index.html.twig',[
            'cart' => $cart->getCart(),
            'totalWt' => $cart->getTotalWt()
        ]);
    }
    
    // cette route nous permet de rester sur la dernière page sur laquelle se trouve l'utilisateur en occurence la page mon-panieer // 
    
    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add($id, Cart $cart, ProductRepository $productRepository, Request $request): Response
    {   $product = $productRepository->findOneById($id);
        $cart->add($product);

        $this ->addFlash(

            'success',
            'Produit correctement ajouté à votre panier '
        );


        return $this->redirect($request->headers->get('referer'));
    }
    
    // création d'une route qui va nous permettre de diminuer la quantité du produit dans notre panier // 
    
    #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')]
    public function decrease($id, Cart $cart): Response
    {   
        $cart->decrease($id);

        $this ->addFlash(

            'success',
            'Produit correctement supprimé de votre panier '
        );


        return $this->redirectToRoute('app_cart');
    }
    // création d'une route qui va nous permettre de vider la quantité du produit dans notre panier // 
    
    #[Route('/cart/remove', name: 'app_cart_remove')]
     public function remove(Cart $cart): Response
    {   
        $cart->remove();

    


        return $this->redirectToRoute('app_home');

    }

    // créattion d'une fonction qui nous permettra le prix totale de nos produits dans le panier // 

    public function getTotalWt()
    {
      
        $cart = $this->requestStack->getSession()->get('cart');
        $price = 0; 
  
          
        if(!isset($cart)){
           return $price; 
        }
  
        
        foreach ($cart as $product){
          
           $price = $price + ($product ['object']->getPriceWt() * $product['qty']);
        }
        return $price; 
      }
  
  
}
