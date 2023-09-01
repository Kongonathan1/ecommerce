<?php
namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'app_admin_category_')]
class CategoryController extends AbstractController
{
    #[Route('/admin/categories', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findBy([], ['order' => 'ASC']);
        return $this->render('admin/categories/index.html.twig', compact('categories'));
    } 
}