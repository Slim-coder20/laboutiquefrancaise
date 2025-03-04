<?php

namespace App\Controller\Account;

use App\Entity\Adress;
use App\Form\AdressUserType;
use App\Form\PasswordUserType;
use App\Repository\AdressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

 class HomeController extends AbstractController
{   
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager; 

    }
    
    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        
        
     return $this->render('account/index.html.twig');
    }
    
    

 


   
}
