<?php

namespace App\Controller;

use App\Form\PasswordUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        
        
     return $this->render('account/index.html.twig');
    }
    
    // création d'une nouvelle route pour la modification du mot de pasee Utilisateur // 

    #[Route('/compte/modifier-mot-de-passe', name: 'app_account_modify_pwd')]
    public function password(): Response
    {
        $form = $this->createForm(PasswordUserType::class);
     
     
     
     return $this->render('account/password.html.twig',[
        'modifyPwd' => $form->createView()
     ]);
    }
}
