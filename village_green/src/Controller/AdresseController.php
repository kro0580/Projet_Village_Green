<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AdresseType;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function add(Request $request): Response
    {
        $adresse = new Adresse();
        $form=$this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $adresse->setAdrCliId($this->getUser());
            $this->entityManager->persist($adresse);
            $this->entityManager->flush();
           return $this->redirectToRoute('adresse');
        }
        return $this->render('client/ajout_adresse.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
