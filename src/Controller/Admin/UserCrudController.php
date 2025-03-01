<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs')
            // ...
        ;
    }

    // cette fonction nous permet de gérer les champs du formulaire 
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstname')->setLabel('Prenom'),
            TextField::new('lastname')->setLabel('Nom'),
            TextField::new('email')->setLabel('Email')->onlyOnIndex()
        ];
    }
    
}
