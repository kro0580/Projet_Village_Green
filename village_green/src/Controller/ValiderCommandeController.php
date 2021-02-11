<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Livraison;
use App\Entity\Passe;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ValiderCommandeController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/commande/merci/{stripSessionId}", name="commande_valider")
     */
    public function index($stripSessionId, Cart $cart): Response
    {
        $commande= $this->entityManager->getRepository(Commande::class)->findOneBycmd_strip_id_session($stripSessionId);
        if (!$commande){
            return $this->redirectToRoute('home');
        }

        if (!$commande->getCmdPayer()){
            //Vider le panier
            $cart->remove();
            //Modification du statut de cmdPayer a true
            $commande->setCmdPayer(1);
            //La livraison
            $livraison = new Livraison();
            $livraison->setLivCmd($commande);
            $diffHours = new DateInterval('PT24H');
            $livraison->setLivDate($commande->getCmdDate()->add($diffHours));
            $livraison->setLivEtat("Encours de preparation");
            $passe = new passe();
            $passe->setPasseCliId($this->getUser());
            $passe->setPasseCmdId($commande->getCmdId());
            $this->entityManager->persist($livraison);
            $this->entityManager->persist($passe);
            $this->entityManager->flush();

            //A revoir(Envoyer un mail de confirmation au client)
        }

        return $this->render('commande_valider/index.html.twig', [
            'commande'=>$commande
        ]);
    }
}
