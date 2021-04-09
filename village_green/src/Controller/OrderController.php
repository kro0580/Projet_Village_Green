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
    // On construit l'entityManager dans le constructeur. De cette façon, on pourra l'utiliser dans les fonctions où l'on en aura besoin 
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/commande", name="order")
     */
    public function index(Cart $cart, Request $request): Response
    {
        // Si le client n'a pas encore renseigné d'adresses, alors je le redirige vers le formulaire de création d'adresses
        if(!$this->getUser()->getCliAdresses()->getValues())
        {
            return $this->redirectToRoute('add_adresse');
        }
        // J'initialise mon formulaire
        // La méthode createform attend en deuxième paramètre une instance de la classe qui est liée au formulaire or ici nous sommes sur un formulaire qui n'est pas lié spécialement à une classe
        // En troisième paramètre, je passe un tableau ou l'on spécifie l'utilisateur en cours
        $form=$this->createForm(OrderType::class, null, [
            // Permet d'afficher les adresses de l'utilisateur en cours. Ce paramètre sera appelé dans OrderType.php pour récupérer les données souhaitées de l'utilisateur en options
            'user'=>$this->getUser()
        ]);
        return $this->render('order/index.html.twig', [
            'form'=>$form->createView(),
            // On récupère ce qu'il y a dans le panier pour l'injecter dans la vue ORDER/INDEX.HTML.TWIG
            'cart'=>$cart->getFull()
        ]);
    }

    // Par sécurité on définit une méthode POST qui permet d'afficher le récapitulatif de la commande uniquement si le formulaire de commande est soumis en POST
    // Cela interdit donc la saisie de l'adresse dans l'url
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
            // On récupère les données indiquées dans le OrderType.php pour l'adresse de facturation, de livraison et les coordonnées du livreur
            $adresseFact=$form->get("adresseFact")->getData();
            $adresseLiv=$form->get("adresseLiv")->getData();
            $livreur=$form->get("livreur")->getData();
            // Enregistrement dans Commande
            // Je définis une nouvelle commande
            $commande = new Commande();
            // On indique la référence de la commande composée de la date de passage de la commande et d'un ID unique
            $reference = $date->format('dmy').'-'.uniqid();
            // On stocke les données dont on a besoin
            $commande->setCmdReference($reference);
            $commande->setCmdDate($date);
            $commande->setCmdCliAdresseFact($adresseFact->getAdrNumRue());
            $commande->setCmdCliCpFact($adresseFact->getAdrCp());
            $commande->setCmdCliVilleFact($adresseFact->getAdrVille());
            $commande->setCmdCliAdresseLiv($adresseLiv->getAdrNumRue());
            $commande->setCmdCliCpLiv($adresseLiv->getAdrCp());
            $commande->setCmdCliVilleLiv($adresseLiv->getAdrVille());
            $commande->setCmdCliCoeff($adresseLiv->getAdrCliId()->getCliCoeff());
            // On définit par défaut le statut de la commande à 0 car à ce stade elle n'est pas encore payée
            $commande->setCmdPayer(0);
            $commande->setCmdLivNom($livreur->getLivNom());
            $commande->setCmdLivPrix($livreur->getLivPrix());
            // J'envoie les données
            $this->entityManager->persist($commande);

            //Enregistrement dans DetailCommande
            // Je récupère mon panier et je boucle sur chaque produit
            foreach ($cart->getFull() as $produit){
                // Je définis une nouveau détail pour la commande
                $detailCmd = new DetailCommande();
                $detailCmd->setDetCmdCmdId($commande);
                // Je récupére le libéllé du produit dans l'entrée produit du panier
                $detailCmd->setDetCmdProduit($produit["produit"]->getProLib());
                // Je récupére le prix d'achat du produit dans l'entrée produit du panier
                $detailCmd->setDetCmdProPrix($produit["produit"]->getProPrixAchat());
                // Je récupére la quantité du produit dans l'entrée quantité du panier
                $detailCmd->setDetCmdProQte($produit["quantite"]);
                // Je calcul le prix total de la commande
                $detailCmd->setDetCmdTotal($produit["quantite"] * $produit["produit"]->getProPrixAchat());
                // J'envoie les données
                $this->entityManager->persist($detailCmd);

            }

            $this->entityManager->flush();

            // On met le return dans le if par sécurité. De cette façon on n'accède au récapitulatif de la commande que si le formulaire a été soumis. Si quelqu'un saisit la route dans l'url, le formulaire n'est pas soumis et il est redirigé vers le panier
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