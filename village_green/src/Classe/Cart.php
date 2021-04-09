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

    // Dès que la classe est appelée, cette fonction constructeur va s'initialiser, on lui injecte SessionInterface et on lui donne la variable $session ainsi que l'EntityManagerInterface et on lui donne la variabe $entityManager
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
        // On set une session qui s'appelle cart et on lui associe un tableau où l'on retrouve les produits du panier
        $this->session->set('cart', $cart);
    }

    // Fonction pour retourner le panier
    public function get()
    {
        return $this->session->get('cart');
    }

    // Fonction pour supprimer le panier
    public function remove()
    {
        return $this->session->remove('cart');
    }

    // Fonction pour supprimer un produit du panier
    public function delete($id)
    {
        $cart=$this->session->get("cart", []);
        unset($cart[$id]);
        return $this->session->set('cart', $cart);
    }

    // Fonction pour diminuer la quantité d'un produit dans le panier
    public function reduir($id)
    {
        $cart=$this->session->get("cart", []);
        if ($cart[$id]>1) // On regarde si le produit à une quantité supérieure à 1
        {
            $cart[$id]--; // S c'est le cas, je souhaite retirer une quantité
        }else{
            unset($cart[$id]); // Si ce n'est pas le cas, je souhaite supprimer mon produit
        }
        return $this->session->set('cart', $cart); // Nouveau panier après les opérations
    }

    // Fonction qui permet de récupérér l'objet produit associé à ce qui est dans le panier
    public function getFull()
    {
        $cartComplete=[];
        if ($this->get()) // On récupère la fonction get() initialisée plus haut
        {
            foreach ($this->get() as $id=>$quantite)
            {
                // Permet l'accès à tous les éléments de l'objet produit
                $product_object= $this->entityManager->getRepository(Produit::class)->find($id);
                if (!$product_object) // Pour éviter qu'un utilisateur puisse ajouter via url un id qui n'existe pas
                {
                   $this->delete($id); // Si l'id du produit tapé par l'utilisateur dans l'url n'existe pas, alors on le supprime du panier
                   continue; // Ensuite on sort de la boucle et on passe au produit suivant
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