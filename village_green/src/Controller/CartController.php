<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/mon-panier", name="cart")
     * @param Cart $cart
     * @return Response
     */
    // Affiche le récapitulatif du panier
    // On embarque la classe Cart que l'on stocke dans la variable $cart
    public function index(Cart $cart): Response
    {
        // Création d'un public function get() dans Cart.php
        return $this->render('cart/index.html.twig', [
            'cart'=>$cart->getFull()
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="add_to_cart")
     * @param Cart $cart
     * @param $id
     * @return Response
     */
    // Ajouter un produit au panier
    // On embarque la classe Cart que l'on stocke dans la variable $cart
    public function add(Cart $cart, $id): Response
    {
        $cart->add($id);
        // On redirige vers le récapitulatif du panier
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/remove", name="remove_to_cart")
     * @param Cart $cart
     * @return Response
     */
    // Supprimer l'ensemble du panier
    // On embarque la classe Cart que l'on stocke dans la variable $cart
    public function remove(Cart $cart): Response
    {
        // Création d'un public function remove() dans Cart.php
        $cart->remove();
        // On redirige vers les produits
        return $this->redirectToRoute('produit_index');
    }

    /**
     * @Route("/cart/delete/{id}", name="delete_to_cart")
     * @param Cart $cart
     * @param $id
     * @return Response
     */
    public function delete(Cart $cart, $id): Response
    {
        $cart->delete($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/reduir/{id}", name="reduir_to_cart")
     * @param Cart $cart
     * @param $id
     * @return Response
     */
    public function reduir(Cart $cart, $id): Response
    {
        $cart->reduir($id);
        return $this->redirectToRoute('cart');
    }
}
