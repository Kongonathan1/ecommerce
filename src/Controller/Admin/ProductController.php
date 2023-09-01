<?php

namespace App\Controller\Admin;

use App\Entity\Images;
use App\Entity\Products;
use App\Form\ProductFormType;
use App\Repository\ProductsRepository;
use App\Services\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(name: 'app_admin_product_')]
class ProductController extends AbstractController
{
    #[Route('/admin/products', name: 'index')]
    public function index(ProductsRepository $productsRepository): Response
    {
        $products = $productsRepository->findBy([], ['created_at' => 'ASC']);
        return $this->render('admin/products/index.html.twig', compact('products'));
    }    
    
    #[Route('/admin/products/add', name: 'add')]
    public function add(Request $request, SluggerInterface $slugger, EntityManagerInterface $em, PictureService $pictureService): Response
    {
        //On crée un nouveau produit
        $product = new Products();

        //On crée le formulaire
        $productForm = $this->createForm(ProductFormType::class, $product);

        //On traie le formulaire
        $productForm->handleRequest($request);

        //On vérifie si le formulaire est soumis et valide
        if($productForm->isSubmitted() && $productForm->isValid()) {
            //On récupère les images 
            $images = $productForm->get('images')->getData();

            foreach($images as $image) {
                //Utilisation du PictureService
                $fichier = $pictureService->add($image);

                $img = new Images();
                $img->setName($fichier);

                $product->addImage($img);
            }


            //On génère le slug
            $slug = $slugger->slug(strtolower($product->getName()));
            $product->setSlug($slug);

            $em->persist($product);
            $em->flush();

            //Message flash
            $this->addFlash('success', 'Votre produit a bien été ajouté !');

            //On redirige vers l'index de l'administration
            return $this->redirectToRoute('app_admin_product_index');
        }

        return $this->render('admin/products/add.html.twig',[
            'productForm' => $productForm->createView()
        ]);
    }

        #[Route('/admin/products/edit-{id}', name: 'edit')]
    public function edit(Request $request, SluggerInterface $slugger, EntityManagerInterface $em, Products $product): Response
    {
        //On crée le formulaire
        $productForm = $this->createForm(ProductFormType::class, $product);

        //On traite le formulaire
        $productForm->handleRequest($request);

        //On vérifie si le formulaire est soumis et valide
        if($productForm->isSubmitted() && $productForm->isValid()) {

            //On génère le slug
            $slug = $slugger->slug(strtolower($product->getName()));
            $product->setSlug($slug);

            $em->persist($product);
            $em->flush();

            //Message flash
            $this->addFlash('success', 'Votre produit a bien été modifié !');

            //On redirige vers l'index de l'administration
            return $this->redirectToRoute('app_admin_product_index');
        }

        return $this->render('admin/products/edit.html.twig',[
            'productForm' => $productForm->createView(),
            'product' => $product
        ]);
    }  
    /*
    #[Route('/admin/products/delete-{id}', name: 'edit')]
    public function delete(Request $request, SluggerInterface $slugger, EntityManagerInterface $em, Products $product): Response
    {
        //On crée le formulaire
        $productForm = $this->createForm(ProductFormType::class, $product);

        //On traite le formulaire
        $productForm->handleRequest($request);

        //On vérifie si le formulaire est soumis et valide
        if($productForm->isSubmitted() && $productForm->isValid()) {

            //On génère le slug
            $slug = $slugger->slug(strtolower($product->getName()));
            $product->setSlug($slug);


            $em->persist($product);
            $em->flush();

            //Message flash
            $this->addFlash('success', 'Votre produit a bien été modifié !');

            //On redirige vers l'index de l'administration
            return $this->redirectToRoute('app_admin_product_index');
        }

        return $this->render('admin/products/edit.html.twig',[
            'productForm' => $productForm->createView(),
            'product' => $product
        ]);
    }
    */
}
