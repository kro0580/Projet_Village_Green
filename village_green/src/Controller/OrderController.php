<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Commande;
use App\Entity\DetailCommande;
use App\Form\OrderType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager =$entityManager;
    }

    /**
     * @Route("/commande", name="order")
     */
    public function index(Cart $cart, Request $request): Response
    {
        $form=$this->createForm(OrderType::class, null, [
            'user'=>$this->getUser()
        ]);
        return $this->render('order/index.html.twig', [
            'form'=>$form->createView(),
            'cart'=>$cart->getFull()
        ]);
    }

    /**
     * @Route("/commande/recapitulatif", name="order_recap", methods={"POST"})
     * @param Cart $cart
     * @param Request $request
     * @return Response
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function add(Cart $cart, Request $request): Response
    {
        $form=$this->createForm(OrderType::class, null, [
            'user'=>$this->getUser()
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $date=new DateTime;
            $adresseFact=$form->get("adresseFact")->getData();
            $adresseLiv=$form->get("adresseLiv")->getData();
            $livreur=$form->get("livreur")->getData();
            // Enregistrement dans Commande()
            $commande = new Commande();
            $reference = $date->format('dmy').'-'.uniqid();
            $commande->setCmdReference($reference);
            $commande->setCmdDate($date);
            $commande->setCmdCliAdresseFact($adresseFact->getAdrNumRue());
            $commande->setCmdCliCpFact($adresseFact->getAdrCp());
            $commande->setCmdCliVilleFact($adresseFact->getAdrVille());
            $commande->setCmdCliAdresseLiv($adresseLiv->getAdrNumRue());
            $commande->setCmdCliCpLiv($adresseLiv->getAdrCp());
            $commande->setCmdCliVilleLiv($adresseLiv->getAdrVille());
            $commande->setCmdCliCoeff($adresseLiv->getAdrCliId()->getCliCoeff());
            $commande->setCmdPayer(0);
            $commande->setCmdLivNom($livreur->getLivNom());
            $commande->setCmdLivPrix($livreur->getLivPrix());
            $this->entityManager->persist($commande);

            //Enregistrement dans DetailCommande()
            foreach ($cart->getFull() as $produit){
                $detailCmd = new DetailCommande();
                $detailCmd->setDetCmdCmdId($commande);
                $detailCmd->setDetCmdProduit($produit["produit"]->getProLib());
                $detailCmd->setDetCmdProPrix($produit["produit"]->getProPrixAchat());
                $detailCmd->setDetCmdProQte($produit["quantite"]);
                $detailCmd->setDetCmdTotal($produit["quantite"] * $produit["produit"]->getProPrixAchat());
                $this->entityManager->persist($detailCmd);

            }

            $this->entityManager->flush();

            return $this->render('order/add.html.twig', [
                'cart'=>$cart->getFull(),
                'livreur'=>$livreur,
                'adresseLiv'=>$adresseLiv,
                'adresseFac'=>$adresseFact,
                'reference'=>$commande->getCmdReference()
            ]);
        }
        return $this->redirectToRoute('cart');

    }
}
