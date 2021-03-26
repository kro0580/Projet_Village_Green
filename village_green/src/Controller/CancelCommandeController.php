<?php

namespace App\Controller;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CancelCommandeController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/commande/erreur/{stripSessionId}", name="commande_cancel")
     */
    public function index($stripSessionId, MailerInterface $mailer): Response
    {
        $commande= $this->entityManager->getRepository(Commande::class)->findOneBycmd_strip_id_session($stripSessionId);
        if (!$commande){
            return $this->redirectToRoute('home');
        }

        //Envoi mail d'échec de la commande
        $client = $this->getUser();
        $mail = $client->getCliEmail();

        $email = (new TemplatedEmail())
            ->from('contact@village_green.org')
            ->to($mail)
            ->subject('Echec de la commande')
            ->htmlTemplate('emails/echec_commande.html.twig')
            ->context([
                'username' => $client->getCliPrenom(),
                'reference' => $commande->getCmdReference(),
            ])
            ;

        $mailer->send($email);

        return $this->render('commande_cancel/index.html.twig', [
            'commande'=>$commande,
        ]);
    }
}
