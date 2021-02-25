<?php
namespace App\Security;

use App\Entity\Produit;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProduitVoter extends Voter {

    //Définition d'un constante contenant la/les action(s) à surveiller
    const EDIT = 'edit';
    const DELETE = 'delete';

    //$attribute = action définie
    //$produit = ce sur quoi se fait le vote
    protected function supports($attribute, $produit)
    {
        //L'attribut n'est pas dans la liste
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }
        //Si $produit n'est pas une instance de Produit => pas dans la liste des produits
        if (!$produit instanceof Client) {
            return false;
        }

        //Si la fonction supports() retourne true, on a donc bien un utilisateur connecté, qui cherche à avoir accès à l'édition du profil. La fonction voteOnAttribute() est ensuite exécutée:
        //Si retourne true, appel de voteOnAttribute()
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        //Récupérer l'utilisateur courant :
        $produit = $token->getUser();

        //Vérifier si l'utilisateur passé en paramètre de la fonction est bien une instance de la classe User :
        if (!$produit instanceof Produit) {
            //L'utilisateur doit être connecté, sinon accès refusé
            return false;
        }

        //Grâce à supports() nous savons que $subject est un objet de la classe Produit, nous le stockons dans une variable :
        $utilisateur = $subject;

        //Ensuite, étudions les différents cas définis (edit pour l'exemple, il peut y en avoir plus selon la gestion des autorisations), et pour chaque cas, on va vérifier que l'utilisateur connecté est bien celui qui est attendu :
        switch ($attribute) {
            case self::EDIT:
                return $produit->getCliId() == $utilisateur->getCliId();
            case self::DELETE:
                return $produit->getCliId() == $utilisateur->getCliId();
        }

        throw new \LogicException('This code should not be reached!');

    }
}