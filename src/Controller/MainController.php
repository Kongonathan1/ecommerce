<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(ProductsRepository $ProductsRepository, CategoryRepository $categoryRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate( $ProductsRepository->paginationQuery(), $request->get('page', 1), 8);
        $categories = $categoryRepository->findBy([], ['order' => 'asc']);
        $name = 'main';
        return $this->render('main/index.html.twig', compact('categories', 'pagination', 'name'));
    }
}
