<?php
namespace App\Security;

use App\Entity\Client;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ClientVoter extends Voter {

    //Définition d'un constante contenant la/les action(s) à surveiller
    const SHOW = 'show';
    const EDIT = 'edit';
    const DELETE = 'delete';

    //$attribute = action définie
    //$client = ce sur quoi se fait le vote
    protected function supports($attribute, $client)
    {
        //L'attribut n'est pas dans la liste
        if (!in_array($attribute, [self::SHOW, self::EDIT, self::DELETE])) {
            return false;
        }
        //Si $client n'est pas une instance de Client => pas dans la liste des clients
        if (!$client instanceof Client) {
            return false;
        }

        //Si la fonction supports() retourne true, on a donc bien un utilisateur connecté, qui cherche à avoir accès à l'édition du profil. La fonction voteOnAttribute() est ensuite exécutée:
        //Si retourne true, appel de voteOnAttribute()
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        //Récupérer l'utilisateur courant :
        $client = $token->getUser();

        //Vérifier si l'utilisateur passé en paramètre de la fonction est bien une instance de la classe User :
        if (!$client instanceof Client) {
            //L'utilisateur doit être connecté, sinon accès refusé
            return false;
        }

        //Grâce à supports() nous savons que $subject est un objet de la classe Client, nous le stockons dans une variable :
        $utilisateur = $subject;

        //Ensuite, étudions les différents cas définis (edit pour l'exemple, il peut y en avoir plus selon la gestion des autorisations), et pour chaque cas, on va vérifier que l'utilisateur connecté est bien celui qui est attendu :
        switch ($attribute) {
            case self::SHOW:
                return $client->getCliId() == $utilisateur->getCliId();
            case self::EDIT:
                return $client->getCliId() == $utilisateur->getCliId();
            case self::DELETE:
                return $client->getCliId() == $utilisateur->getCliId();
        }

        throw new \LogicException('This code should not be reached!');

    }
}