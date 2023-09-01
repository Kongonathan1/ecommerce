<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'app_category_')]
class CategoryController extends AbstractController
{
    #[Route('/category/{slug}', name: 'list')]
    public function list(Category $category, CategoryRepository $categoryRepository): Response
    {
        $products = $category->getProducts();
        $categories = $categoryRepository->findBy([], ['order' => 'asc']);
        $name = 'list';
        return $this->render('main/index.html.twig', compact('category', 'products', 'name', 'categories'));
    }
}
