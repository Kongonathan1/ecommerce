<?php

namespace App\Controller;

use App\Entity\OrderDetails;
use App\Entity\Orders;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'app_order_')]
class OrderController extends AbstractController
{
    #[Route('/order/add', name: 'add')]
    public function add(SessionInterface $session, ProductsRepository $productsRepository, EntityManagerInterface $entityManager): Response
    {
        $panier = $session->get('panier', []);

        if(empty($panier)) {
            $this->addFlash('danger', "Votre panier est vide");
        }

        try {
            $order = new Orders();
    
            //On crée notre commande
            //Juste pour l'exemple, la référence sera aléatoire
    
            $order->setUsers($this->getUser())
                ->setReference(uniqid());
    
            foreach($panier as $item => $quantity) {
                $orderDetails = new OrderDetails();
                //Oncherche le produit
                $product = $productsRepository->find($item);
                if($product === null) {
                    $this->addFlash('danger', "Ce produit n'existe pas dans notre boutique");
                }
    
                //Si le produit existe on crée les détails de commande
                $orderDetails->setProducts($product)
                            ->setPrice($product->getPrice())
                            ->setQuantity($quantity);
    
                //On insère les détails de commande dans notre commande
                $order->addOrderDetail($orderDetails);
            }
    
            $entityManager->persist($order);
            $entityManager->flush();
            //On vide le panier
            $session->remove('panier');

            $this->addFlash('success', "Votre commande a été créée avec succès");
        } catch (ORMException $e) {
            $this->addFlash('danger', "Votre commande a échouée");
        }

        
        return $this->render('cart/index.html.twig');
    }
}
