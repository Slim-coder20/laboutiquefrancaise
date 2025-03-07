<?php

namespace App\Controller;

use DateTime;
use App\Classe\Cart;
use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\OrderDetail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class OrderController extends AbstractController
{   
    /**
     * 1 ère étape du tunnel d'achet 
     * choix de l'adresse de livraison et du transporteur
     */

    #[Route('/commande/livraison', name: 'app_order')]
    public function index(Cart $cart): Response
    {   
        $adresses = $this->getUser()->getAdresses();
        
        
        if(count($adresses) == 0){
            return $this->redirectToRoute('app_account_adress_form');
        }
      
        
        $form = $this->createForm(OrderType::class, null, [

            'adresses' => $adresses,
            'action' => $this->generateUrl('app_order_summary')


        ]);


        return $this->render('order/index.html.twig', [
            'deliveryForm' => $form->createView(),
        ]);
   
   }
    /**
     * 2ème  étape du tunnel d'achat 
     * Récap de la commande de l'utilisateur 
     * Insertion en base de donnée 
     * préparation du paiement vers stripe 
     */

     #[Route('/commande/recapitulatif', name: 'app_order_summary')]
     public function add(Request $request, Cart $cart, EntityManagerInterface $entityManager): Response
     {   
        
        
        if($request->getMethod() != 'POST'){
            return $this->redirectToRoute('app_cart');
        }
        $products = $cart->getCart(); 
       

        $form = $this->createForm(OrderType::class, null, [
            
        'adresses' => $this->getUser()->getAdresses()
    
    ]);
    $form->handleRequest($request);
    
    if($form->isSubmitted() && $form->isValid()){
        /**
         * Création de la chaine adresse : 
         */
        $addressobj = ($form->get('addresses')->getData());
        
        $address = $addressobj->getFirstname().''.$addressobj->getLastname().'<br/>';
        $address.= $addressobj->getAdress().'<br/>';
        $address.= $addressobj->getPostal().' '.$addressobj->getCity().'<br/>';
        $address.= $addressobj->getCountry().'<br/>';
        $address.= $addressobj->getPhone();

             
        /**
         * Stocker la commande et ses détails dans la base de donnée 
         */


        $order = new Order();
       
        $order->setUser($this->getUser());
        $order->setCreatedAt(new \DateTime());
        $order->setState(1);
        $order->setCarrierName($form->get('carriers')->getData()->getName());
        $order->setCarrierPrice($form->get('carriers')->getData()->getPrice());
        $order->setDelivery($address);
      
        
        foreach ($products as $product)
        {
        $orderDetail = new OrderDetail();
        $orderDetail->setProductName($product['object']->getName());
        $orderDetail->setProductIllustration($product['object']->getIllustration());
        $orderDetail->setProductPrice($product['object']->getPrice());
        $orderDetail->setProductTva($product['object']->getTva());
        $orderDetail->setProductQuantity($product['qty']);
        $order->addOrderDetail($orderDetail);
       
    
        }
        
        $entityManager->persist($order);
        $entityManager->flush();
    }
    
    return $this->render('order/summary.html.twig',[
        'choices' => $form->getData(),
        'cart' => $products,
        'order' => $order, 
        'totalWt' => $cart->getTotalWt()
    ]);    
   
    
    }
   

 }
