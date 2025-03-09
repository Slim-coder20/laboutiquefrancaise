<?php

namespace App\Controller;
use App\Classe\Mail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
       
        $mail = new Mail();
        $mail->send('laboutiquefrancaise@yopmail.com', 'Jhon Doe', 'Bonjour test de ma classe mail', 'Mon premier email');
        
        return $this->render('home/index.html.twig');
    }
}
