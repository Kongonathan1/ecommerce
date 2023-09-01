<?php
namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'app_admin_')]
class IndexController extends AbstractController
{
    #[Route('/admin', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/main/index.html.twig');
    } 
}