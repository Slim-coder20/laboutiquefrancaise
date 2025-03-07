<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PayementController extends AbstractController
{
    #[Route('commande/paiement/{id_order}', name: 'app_payement')]
    public function index($id_order, OrderRepository $orderRepository): Response
    {   
      Stripe::setApiKey('sk_test_51QzfqAPoOLevBpHx9yEBt3E7Efye9OcYbR4T0qFQ2GWJy5KPywFbACqOhgPb1iVmtOmcZnAEKkBnJTf52fygXLbJ00YQYHj2LL');
      $YOUR_DOMAIN = 'http://127.0.0.1:8000';


        $order = $orderRepository->findOneById($id_order); 
        //dd($order);

        $product_for_stripe = []; 

          foreach ($order->getOrderDetails() as $product ){
          $product_for_stripe[] = [
            'price_data' => [
            
            'currency' => 'eur', 
            'unit_amount' => number_format($product->getProductPriceWt() * 100, 0, '', ''),
            'product_data' => [
                'name' => $product->getProductName(),
                'images' => [
                  $YOUR_DOMAIN.'/uploads/'.$product->getProductIllustration()
                ]
            ]

            ],
          'quantity' => $product->getProductQuantity(),
            
          
          ];
          }
            //dd($order);
            $product_for_stripe[] = [
            'price_data' => [
            
            'currency' => 'eur', 
            'unit_amount' => number_format($order->getCarrierPrice() * 100, 0, '', ''),
            'product_data' => [
                'name' => 'Transporteur : '.$order->getCarrierName(),
               
            ]

            ],
          'quantity' => 1,
            
          
          ];
         $checkout_session = Session::create ([
         'customer_email' => $this->getUser()->getEmail(), 
        'line_items' => $product_for_stripe,
        'mode' => 'payment',
        'success_url' => $YOUR_DOMAIN . '/success.html',
        'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
      ]);
      
       
       
      
       return $this->redirect($checkout_session->url);
    
    }
}
