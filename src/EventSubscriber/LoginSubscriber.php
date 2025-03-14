<?php 

namespace App\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSubscriber implements EventSubscriberInterface
{   
    private $em;
    private $security;
    
    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->security = $security;
    }




    public function onLogin()
    {
        // On va écrire le code pour mettre a jour la date de la dernière connexion // 
     
        $user = $this->security->getUser();
        $user->setLastLoginAt(new \DateTime());
        $this->em->flush();
      
      
    
    }
    
    
    
    
    public static function getSubscribedEvents(): array
    {
        // Dans cette méthode on va définir les événements auquel on souhaite souscrire 
        return [
           
            LoginSuccessEvent::class => 'onLogin'
        
        ];
     
    
    }



}

