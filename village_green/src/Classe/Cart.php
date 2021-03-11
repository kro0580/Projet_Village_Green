<?php
namespace App\Classe;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

// Cette classe contient toute la mécanique du panier
class Cart
{
    private $session;
    private $entityManager;

    // Dès que la classe est appelée, cette fonction constructeur va s'initialiser, on lui injecte SessionInterface et on lui donne la variable $session
    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    // Fonction pour ajouter un produit au panier
    public function add($id)
    {
        // On stocke le panier dans $cart, on va chercher la session cart
        $cart=$this->session->get("cart", []);
        // Si il y a dejà un produit inséré dans le panier
        if (!empty($cart[$id])){
            // Tu incrémentes de 1 la quantité
            $cart[$id]++;
        }else{
            // Si ce n'est pas le cas tu ajoutes 1 à la quantité
            $cart[$id]=1;
        }
        // On set une session qui s'appelle cart et lui associer un tableau où l'on retrouve les produits du panier
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
            $cart[$id]--; // Reduire si la quantité dans le panier est superieur à 1.
        }else{
            unset($cart[$id]); // Retirer l'article dans le panier s'il est égal à 1.
        }
        return $this->session->set('cart', $cart); // Nouveau $cart après les opérations
    }

    public function getFull()
    {
        $cartComplete=[];
        if ($this->get()) // Pour ne pas faire le foreach lorsque le panier est vide
        {
            foreach ($this->get() as $id=>$quantite)
            {
                // Permet l'accès à tous les éléments de l'objet produit
                $product_object= $this->entityManager->getRepository(Produit::class)->find($id);
                if (!$product_object) // Pour éviter qu'un utilisateur puisse ajouter via url un id qui n'existe pas
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