<?php 
namespace App\Classe;

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
      if($cart[$product->getId()]){
         
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


 }