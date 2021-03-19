<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Adresse;
use App\Form\AdresseType;
use http\Client\Curl\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdresseController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("client/adresse", name="adresse")
     */
    public function index(): Response
    {
        return $this->render('client/adresse.html.twig');
    }

    /**
     * @Route("client/ajouter-une-adresse", name="add_adresse")
     */
    public function add(Cart $cart, Request $request): Response
    {
        // On instancie l'entité Adresse
        $adresse = new Adresse();

        // On créé l'objet formulaire qui prend comme paramètres le type et les données à envoyer
        $form=$this->createForm(AdresseType::class, $adresse);

        // On récupère les données saisies
        $form->handleRequest($request);

        // On vérifie si le formulaire a été envoyé et si les données sont valides
        if ($form->isSubmitted() && $form->isValid())
        {
            // Ici le formulaire a été envoyé et les données sont valides
            // Je récupère l'ID de l'utilisateur qui a saisi l'adresse
            $adresse->setAdrCliId($this->getUser());
            // On met en place toutes les données dans l'objet
            // Pour pouvoir utiliser l'EntityManager, il faut créer le constructeur que l'on retrouve en haut du fichier
            $this->entityManager->persist($adresse);
            // On écrit dans la BDD
            $this->entityManager->flush();
            // Message flash
            $this->addFlash('success','Votre adresse a bien été ajoutée');

            // Si j'ai des produits dans mon panier, il y a une redirection vers la commande
            if($cart->get())
            {
                return $this->redirectToRoute('order');
            }
            // Sinon on redirige vers la page d'affichage des adresses
            else
            {
                return $this->redirectToRoute('adresse');
            }
            
        }
        return $this->render('client/ajout_adresse.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("client/modifier-une-adresse/{id}", name="edit_adresse")
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, $id)
    {
        // On recherche l'ID de l'adresse que l'on souhaite modifier
        $adresse= $entityManager->getRepository(Adresse::class)->find($id);

        // Si l'adresse n'existe pas et si l'utilisateur cherche à accéder à une adresse qui n'est pas la sienne, on le redirige vers l'affichage de ses adresses
        if (!$adresse || $adresse->getAdrCliId() != $this->getUser()) {
            return $this->redirectToRoute('adresse');
        }

        // On créé l'objet formulaire qui prend comme paramètres le type et les données à envoyer
        $form=$this->createForm(AdresseType::class, $adresse);

        // On récupère les données saisies
        $form->handleRequest($request);

        // On vérifie si le formulaire a été envoyé et si les données sont valides
        if ($form->isSubmitted() && $form->isValid())
        {
            // On écrit dans la BDD
            $this->entityManager->flush();
            // Message flash
            $this->addFlash(
                'success',
                'Votre adresse a bien été modifiée'
            );

            return $this->redirectToRoute('adresse');
        }
        return $this->render('client/ajout_adresse.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("client/suprimer-adresse{id}", name="delete_adresse")
     */
    public function delete($id)
    {
        // Nous allons utiliser l'objet EntityManager de Doctrine. Il nous permet d'envoyer et d'aller chercher des objets dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        // On recherche l'ID de l'adresse
        $adresse= $entityManager->getRepository(Adresse::class)->find($id);
        // Si l'adresse existe et que je suis bien l'utilisateur de celle-ci
        if ($adresse && $adresse->getAdrCliId() == $this->getUser()) {
            // On supprime l'adresse
            $entityManager->remove($adresse);
            // On met à jour la BDD
            $entityManager->flush();
        }

        return $this->redirectToRoute('home');
    }
}
