<?php

namespace App\Controller;

use DateInterval;
use App\Classe\Cart;
use App\Entity\Passe;
use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Livraison;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ValiderCommandeController extends AbstractController
{
    // Pour interroger Doctrine
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/commande/merci/{stripSessionId}", name="commande_valider")
     */
    public function index($stripSessionId, Cart $cart, MailerInterface $mailer): Response
    {
        $commande= $this->entityManager->getRepository(Commande::class)->findOneBycmd_strip_id_session($stripSessionId);
        // Si la commande n'est pas trouvée, on redirige vers la page d'accueil
        if (!$commande){
            return $this->redirectToRoute('home');
        }

        // Si ma commande est en statut non payée
        if (!$commande->getCmdPayer()){
            //Vider le panier
            $cart->remove();
            //Modification du statut de cmdPayer a true donc on bascule de 0 à 1
            $commande->setCmdPayer(1);
            //La livraison
            $livraison = new Livraison();
            $livraison->setLivCmd($commande);
            $diffHours = new DateInterval('PT24H');
            $livraison->setLivDate($commande->getCmdDate()->add($diffHours));
            $livraison->setLivEtat("En cours de preparation");
            $passe = new passe();
            $passe->setPasseCliId($this->getUser());
            $passe->setPasseCmdId($commande->getCmdId());
            $this->entityManager->persist($livraison);
            $this->entityManager->persist($passe);
            $this->entityManager->flush();

            //Envoi mail de confirmation de commande
            $client = $this->getUser();
            $mail = $client->getCliEmail();

            $email = (new TemplatedEmail())
                ->from('contact@village_green.org')
                ->to($mail)
                ->subject('Confirmation de commande')
                ->htmlTemplate('emails/conf_commande.html.twig')
                ->context([
                    'username' => $client->getCliPrenom(),
                    'reference' => $commande->getCmdReference(),
                    'adresse' => $commande->getCmdCliAdresseLiv(),
                    'cp' => $commande->getCmdCliCpLiv(),
                    'ville' => $commande->getCmdCliVilleLiv(),
                ])
                ;

            $mailer->send($email);
        }

        return $this->render('commande_valider/index.html.twig', [
            'commande'=>$commande
        ]);
    }
}
