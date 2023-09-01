<?php
namespace App\Controller;

use App\Repository\ProductsRepository;
use App\Entity\Products;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


#[Route(name: 'app_cart_')]
class CartController extends AbstractController
{
    #[Route('/cart', name: 'index')]
    public function index(ProductsRepository $productRepository, SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);

        $datas = [];
        $total = 0;
        foreach($panier as $id => $quantity) {
            $product = $productRepository->find($id);
            $datas[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrice() * $quantity; 
        }
        
        return $this->render('cart/index.html.twig', compact('datas', 'total'));


    } 

    #[Route('/cart/add/{slug}/{id}', name: 'add')]
    public function add(SessionInterface $session, Products $product): Response
    {
        $session->get('panier', []);

        $id = $product->getId();
        
        $panier = $session->get('panier', []);

        //Si le produit n'est déja dans le panier on l'ajoute sinon on l'incrémente
        empty($panier[$id]) ? $panier[$id] = 1 : $panier[$id]++;

        $session->set('panier', $panier);
        //On fait un message flash
        $this->addFlash('success', "Votre produit a bien été ajouté au panier");

        return $this->render('product/details.html.twig', compact('product'));
    } 
    
    #[Route('/cart/new/{id}', name: 'new')]
    public function new(SessionInterface $session, Products $product): Response
    {
        $session->get('panier', []);

        $id = $product->getId();
        
        $panier = $session->get('panier', []);

        //Si le produit n'est déja dans le panier on l'ajoute sinon on l'incrémente
        empty($panier[$id]) ? $panier[$id] = 1 : $panier[$id]++;

        $session->set('panier', $panier);

        return $this->redirectToRoute('app_cart_index');
    }
    
    #[Route('/cart/remove/{id}', name: 'remove')]
    public function remove(SessionInterface $session, Products $product): Response
    {
        $session->get('panier', []);

        $id = $product->getId();
        
        $panier = $session->get('panier', []);

        //S'il y'a un produit dans le panier on l'enlève sinon on le décrémente
        if(!empty($panier[$id])) {
            if($panier[$id] === 1) {
                unset($panier[$id]);
            } else {
                $panier[$id]--;
            }
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/cart/delete/{id}', name: 'delete')]
    public function delete(SessionInterface $session, Products $product): Response
    {
        $session->get('panier', []);

        $id = $product->getId();
        
        $panier = $session->get('panier', []);

        //Si le produit n'est déja dans le panier on l'ajoute sinon on l'incrémente
        if(!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('app_cart_index');
    } 

    #[Route('/cart/clear', name: 'clear')]
    public function clear(SessionInterface $session): Response
    {
        $session->remove('panier');

        return $this->redirectToRoute('app_cart_index');
    } 
}