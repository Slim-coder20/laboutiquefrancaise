<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $user = new User();
        
        // instanciation du formulaire d'inscription // 
        $form = $this->createForm(RegisterUserType::class, $user); 
        
        // On demande ici à cette fonction (handleRequest) d'aller écouter la request pour aller plus loin // 
        $form->handleRequest($request);
        
        // On vérifie que le formulaire est bien saisie et qu'il est valide // 
        if($form->isSubmitted() && $form->isValid()){

            // persist permet de figer les données avant de les envoyer vers notre BDD 
            //dd($form->getData());
            $em -> persist($user); 
            $em -> flush(); 
            $this ->addFlash(

                'success',
                'Votre inscription a été bien effectué. Veuillez vous connecter'
            );
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('register/index.html.twig', [
            
            // c'est le nom de la variable qu'on va envoyer au fichier twig pour la vue du formulaire // 
            'registerForm' => $form->createView()
        ]);
    }
}
