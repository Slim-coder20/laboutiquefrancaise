<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends AbstractController
{   
    /**
     * cette route sert à connecter l'utilisateur a son compte 
     */

    #[Route('/connexion', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {   
        // permet de récupérer la dernière erreur d'authentification lors de la soumission d'un formulaire de connexion.
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // permet de vérifier le dernier email saisie par l'utilisateur  
        $lastUsername = $authenticationUtils->getLastUsername();
        
        
        return $this->render('login/index.html.twig',[
            
            'error' => $error,
            'last_username' => $lastUsername
        ]);
    }
/**
 * cette route nous permet de déconnecter notre utilisateur 
 */

    #[Route('/deconnexion', name: 'app_logout', methods:['GET'])]
    public function logout(): never
    {
        

    }
}
