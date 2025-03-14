<?php

namespace App\Controller;
use DateTime;
use App\Classe\Mail;
use App\Repository\UserRepository;
use App\Form\ResetPasswordFormType;
use App\Form\ForgotPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ForgotPasswordController extends AbstractController
{   // Puisque on va utiliser l'entity manger dans l'index et dans le reset, on va le       déclarer dans le constructeur 
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/mot-de-passe-oublie', name: 'app_password')]
    public function index(Request $request, UserRepository $userRepository, ): Response
    {   
        // 1. Afficher le formulaire de demande de réinitialisation de mot de passe
        $form = $this->createForm(ForgotPasswordFormType::class);
        
        // 2. Traiter le formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            // 2. Si l'email renseigné par l'utilisateur est valide et se trouve dans la base de données
            $email = $form->get('email')->getData();
            $user = $userRepository->findOneBy(['email' => $email]);

            if ($user) {
                // 3. Envoyer une modification de mot de passe à l'utilisateur
                
                     // 4. créer un token qu'on va stocker dans la base de données
                    $token = bin2hex(random_bytes(15));
                    $user->setToken($token);    

                    $date = new DateTime();
                    $date->modify('+10 minutes');
                    $user->setTokenExpireAt($date);
                    //dd($user);

                    
                    $this->entityManager->flush();

                    // on génère l'url qu'on va envoyer dans le mail pour permettre à l'utilisateur de réinitialiser son mot de passe
                     
                
                
                // 6. Envoyer un email à l'utilisateur avec un lien contenant le token
                $mail = new Mail();
                $vars = [
                    // on génère l'url qu'on va envoyer dans le mail pour permettre à l'utilisateur de réinitialiser son mot de passe
                    'link' => $this->generateUrl('app_password_reset', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL)
                ];
                
                $mail->send($user->getEmail(), $user->getFirstname().' '.$user->getLastname(),'Modification de votre mot de passe ', 'forgotpassword.html', $vars);

                $this->addFlash('success', 'Un email de réinitialisation de mot de passe vous a été envoyé.');
            } else {
                $this->addFlash('danger', 'Aucun utilisateur trouvé avec cet email.');
            }
        }

        return $this->render('password/index.html.twig', [
            'forgotPasswordForm' => $form->createView()
        ]);
    }

    #[Route('/mot-de-passe/reset/{token}', name: 'app_password_reset')]
    public function reset(Request $request, UserRepository $userRepository, $token): Response
    {   
        // 3. Vérifier si le token est valide
        if (!$token) {
            return $this->redirectToRoute('app_password');
        }
        // 5 on va comparer les dates pour voir si le token est encore valide
        $now = new DateTime();
        // 4. Si le token est valide, on va chercher l'utilisateur correspondant
        $user = $userRepository->findOneByToken($token);
        if (!$user || $now > $user->getTokenExpireAt()) {
            return $this->redirectToRoute('app_password');
        }

        
   

        $form = $this->createForm(ResetPasswordFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setToken(null);
            $user->setTokenExpireAt(null);
            $this->entityManager->flush(); 
            $this->addFlash(
            'success', 
            'Votre mot de passe a bien été mis à jour.');

           
            return $this->redirectToRoute('app_login');
        }
        
        
        
        // 5. Afficher le formulaire de modification de mot de passe
        return $this->render('password/reset.html.twig', [
            'form' => $form->createView()
        ]);
        
    
    }
}
