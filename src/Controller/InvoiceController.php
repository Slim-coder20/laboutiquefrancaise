<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceController extends AbstractController
{
    /*
     * IMPRESSION FACTURE PDF pour un utilisateur connecté
     * Vérification de la commande pour un utilisateur donné
     */
    #[Route('/compte/facture/impression/{id_order}', name: 'app_invoice_customer')]
    public function printForCustomer(OrderRepository $orderRepository, $id_order): Response
    {
        // 1. Vérification de l'objet commande - Existe ?
        $order = $orderRepository->findOneById($id_order);
    
        if (!$order) {
            return $this->redirectToRoute('app_account');
        }
    
        // 2. Vérification de l'objet commande - Ok pour l'utilisateur ?
        if ($order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account');
        }
    
        // --- CONFIGURATION DOMPDF ---
        $options = new Options();
        $options->set('defaultFont', 'Roboto');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isFontSubsettingEnabled', true);
    
        $dompdf = new Dompdf($options);
    
        // --- RENDER HTML ---
        $html = $this->renderView('invoice/index.html.twig', [
            'order' => $order
        ]);
    
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait'); // Vous pouvez tester aussi 'landscape' si besoin
        $dompdf->render();
    
        $dompdf->stream('facture.pdf', [
            'Attachment' => false
        ]);
    
        exit();
    }
    


    /*
     * IMPRESSION FACTURE PDF pour un administrateur connecté
     * Vérification de la commande pour un utilisateur donné
     */
    #[Route('/admin/facture/impression/{id_order}', name: 'app_invoice_admin')]
    public function printForAdmin(OrderRepository $orderRepository, $id_order): Response
    {
        // 1. Vérification de l'objet commande - Existe ?
        $order = $orderRepository->findOneById($id_order);
    
        if (!$order) {
            return $this->redirectToRoute('admin');
        }
    
        // --- CONFIGURATION DOMPDF ---
        $options = new Options();
        $options->set('defaultFont', 'Roboto');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isFontSubsettingEnabled', true);
    
        $dompdf = new Dompdf($options);
    
        // --- RENDER HTML ---
        $html = $this->renderView('invoice/index.html.twig', [
            'order' => $order
        ]);
    
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        $dompdf->stream('facture.pdf', [
            'Attachment' => false
        ]);
    
        exit();
    }
    
}
