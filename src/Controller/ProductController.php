<?php

namespace App\Controller;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'app_product_')]
class ProductController extends AbstractController
{
    #[Route('/produits', name: 'index')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig');
    }    
    
    #[Route('/produits/{slug}', name: 'details')]
    public function details(Products $product): Response
    {
        return $this->render('product/details.html.twig', compact('product'));
    }
}
