<?php

namespace App\Controller;

use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{   
    /**
     * 1 ère étape du tunnel d'achet 
     * choix de l'adresse de livraison et du transporteur
     */

    #[Route('/commande/livraison', name: 'app_order')]
    public function index(): Response
    {   
        $adresses = $this->getUser()->getAdresses();
        if(count($adresses) == 0){
            return $this->redirectToRoute('app_account_adress_form');
        }
        
        $form = $this->createForm(OrderType::class, null, [

            'adresses' => $adresses


        ]);


        return $this->render('order/index.html.twig', [
            'deliveryForm' => $form->createView(),
        ]);
    }
}
