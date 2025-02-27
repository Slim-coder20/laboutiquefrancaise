<?php

namespace App\Controller;

use App\Form\RegisterUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(): Response
    {
        
        // création du formulaire d'inscription // 
        $form = $this->createForm(RegisterUserType::class); 
        
        
        
        
        
        
        return $this->render('register/index.html.twig', [
            
            // c'est le nom de la variable qu'on va envoyer au fichier twig pour la vue du formulaire // 
            'registerForm' => $form->createView()
        ]);
    }
}
