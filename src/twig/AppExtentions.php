<?php 

namespace App\twig;

use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtentions extends AbstractExtension implements GlobalsInterface
{
    private $categoryRepository; 
    
    public function __construct(CategoryRepository $categoryRepository){
        
        $this ->categoryRepository = $categoryRepository; 
    }

    public function  getFilters()
    {
        return [
            new TwigFilter ('price', [ $this, 'formatPrice']),
        
        
        ];
    }
    public function formatPrice($number)
    {
        return number_format ($number,'2', ',' ). '€';
    
    }
    // cette méthode getGlobals va nous permettre de créé des variables globales que nous allons pouvoir utiliser partout dans notre environement twig // 
    public function getGlobals(): array 

    {
        return [
            'allCategories' => $this->categoryRepository->findAll()

        ];
    }
}