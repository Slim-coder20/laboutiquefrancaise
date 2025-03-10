<?php

namespace App\Controller\Admin;

use App\Entity\Header;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class HeaderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }

 
    public function configureFields(string $pageName): iterable
    {

        $required = true; 
        if ($pageName =='edit'){
            $required = false;
        }
        


        return [
            
            TextField::new('title', label:'Titre'),
            TextareaField::new('content', label:'Contenu'),
            TextField::new('buttonTitle', label:'Titre du boutton'),
            TextField::new('buttonLink', label:'URL du boutton'),
            ImageField::new('illustration')
            ->setLabel('Image de fond du header')
            ->setHelp('image de fond du header en JPEG PNG JPG')
            ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')
            ->setBasePath('/uploads')->setUploadDir('/public/uploads')
            ->setRequired($required)
            ,

           
        ];
    }
    
}
