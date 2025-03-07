<?php

namespace App\Controller;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PayementController extends AbstractController
{
    #[Route('commande/paiement', name: 'app_payement')]
    public function index(): Response
    {   
       Stripe::setApiKey('sk_test_51QzfqAPoOLevBpHx9yEBt3E7Efye9OcYbR4T0qFQ2GWJy5KPywFbACqOhgPb1iVmtOmcZnAEKkBnJTf52fygXLbJ00YQYHj2LL');
       $YOUR_DOMAIN = 'http://127.0.0.1:8000';
       
      
       
       $checkout_session = Session::create ([
        'line_items' => [[
          # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
          'price_data' => [
            
            'currency' => 'eur', 
            'unit_amount' => '1500', 
            'product_data' => [
                'name' => 'produit de test'
            ]

            ],
          'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => $YOUR_DOMAIN . '/success.html',
        'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
      ]);
      
       
       
       //die('Ok');
       return $this->redirect($checkout_session->url);
    
    }
}
