<?php 
namespace App\Classe;

use Doctrine\ORM\Mapping\Id;
use Symfony\Component\HttpFoundation\RequestStack;
 class Cart{

   // ici on a créé un constructeur et on lui injester la RequestStack qui va nous permettre d'aller chercher mes sessions 

   public function __construct(private RequestStack $requestStack)
   {

      
   }

    public function add($product){
      // On appelle la session de symfony 
     
      // ça va nous permettre de récupérer le panier en cours et de lui rajouter 
      $cart = $this->requestStack->getSession()->get('cart');
      
      // Ajouter une quantité +1 à mon produit // 
      if(isset($cart[$product->getId()])){
         
         $cart [$product->getId()] = [
            'object' => $product,
            'qty' => $cart[$product->getId()]['qty'] + 1
   
         ];
      }
      else{
         $cart [$product->getId()] = [
            'object' => $product,
            'qty' => 1
   
         ];

      }
     

      // créer ma session Cart // 
      $this->requestStack->getSession()->set('cart',$cart);

      
    }
    public function getCart()
    {
      return $this->requestStack->getSession()->get('cart');
    }

    public function remove()
    {
      return $this->requestStack->getSession()->remove('cart');

    }

    // c'est une fonction qui va nous permettre de retirer des articles dans mon panier // 

    public function decrease($id){
      $cart = $this->requestStack->getSession()->get('cart');
      if($cart[$id]['qty'] > 1){
         $cart[$id]['qty'] =  $cart[$id]['qty'] - 1; 
      
      }else {
         unset($cart[$id]);
      }
      $this->requestStack->getSession()->set('cart',$cart);
     
    }

    // c'est une fonction qui va nous permettre d'avoir la quantité totale de nos produit dans le panier // 

    public function fullQuantity()
    { 
      
      if(!isset($cart)){
         return $quantity; 
      }

      $cart = $this->requestStack->getSession()->get('cart');
      $quantity = 0; 
      foreach ($cart as $product){
        
         $quantity = $quantity + $product['qty'];
      }
     return $quantity; 
     

    }
   
    // Cette fonction nous permettra de calculer le prix TTC de tous nos produits dans le panier // 

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