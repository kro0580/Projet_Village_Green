<?php

namespace App\Controller;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeCancelController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/commande/erreur/{stripSessionId}", name="commande_cancel")
     */
    public function index($stripSessionId): Response
    {
        $commande= $this->entityManager->getRepository(Commande::class)->findOneBycmd_strip_id_session($stripSessionId);
        if (!$commande){
            return $this->redirectToRoute('home');
        }
        return $this->render('commande_cancel/index.html.twig', [
            'commande'=>$commande,
        ]);
    }
}
