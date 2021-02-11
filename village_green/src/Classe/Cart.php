<?php
namespace App\Classe;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private $session;
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->session = $session;
        $this->entityManager=$entityManager;
    }
    public function add($id)
    {
        $cart=$this->session->get("cart", []);
        if (!empty($cart[$id])){
            $cart[$id]++;
        }else{
            $cart[$id]=1;
        }
        $this->session->set('cart', $cart);
    }

    public function get()
    {
        return $this->session->get('cart');
    }

    public function remove()
    {
        return $this->session->remove('cart');
    }
    public function delete($id)
    {
        $cart=$this->session->get("cart", []);
        unset($cart[$id]);
        return $this->session->set('cart', $cart);
    }
    public function reduir($id)
    {
        $cart=$this->session->get("cart", []);
        if ($cart[$id]>1)
        {
            $cart[$id]--; // Reduir si la quantité dans le panier est superieur a 1.
        }else{
            unset($cart[$id]); // Retirer l'article dans le panier s'il est égal a 1.
        }
        return $this->session->set('cart', $cart); // Nouveau $carte après les operations
    }

    public function getFull()
    {
        $cartComplete=[];
        if ($this->get()) // Pour n'est pas faire le foreache lorsque le panier est vide
        {
            foreach ($this->get() as $id=>$quantite)
            {
                $product_object= $this->entityManager->getRepository(Produit::class)->find($id);
                if (!$product_object) // Pour eviter que un utilisateur puisse ajouter via url un id qui n'existe pas
                {
                   $this->delete($id);
                   continue;
                }
                $cartComplete[]=[
                    'produit'=> $product_object,
                    'quantite'=>$quantite
                ];
            }
        }
        return $cartComplete;
    }
}