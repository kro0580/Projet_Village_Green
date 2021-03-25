<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Commande;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripController extends AbstractController
{
    /**
     * @Route("/commande/create-session/{reference}", name="strip_create_session")
     * @param EntityManagerInterface $entityManager
     * @param Cart $cart
     * @param $reference
     * @return Response
     * @throws ApiErrorException
     */
    public function index(EntityManagerInterface $entityManager, Cart $cart, $reference): Response
    {
        header('Content-Type: application/json');
        $YOUR_DOMAIN = 'https://127.0.0.1:8000';
        $produitStrip = [];
        // On recherche les produits en BDD par référence
        $commande = $entityManager->getRepository(Commande::class)->findOneBycmd_reference($reference);
        // Si il n'y a pas de commande, on affiche un message d'erreur dans ORDER/ADD.HTML.TWIG
        if (!$commande){
             new JsonResponse(['error' => 'commande']);
        }
        foreach ($commande->getCmdDetCmdId()->getvalues() as $produit){
            $Objet_produit= $entityManager->getRepository(Produit::class)->findOneBypro_lib($produit->getDetCmdProduit());
            // Paramétrage de la vue Stripe pour la commande
            $produitStrip[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $produit->getDetCmdProPrix() * 100,
                    'product_data' => [
                        'name' => $produit->getDetCmdProduit(),
                        // Les images ne s'affichent pas car on est en local
                        'images' => [$YOUR_DOMAIN."/img/PRODUITS/".$Objet_produit->getProPhoto()],
                    ],
                ],
                'quantity' => $produit->getDetCmdProQte()
            ];
        }

        // Paramétrage de la vue Stripe pour le transporteur
        $produitStrip[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $commande->getCmdLivPrix(),
                'product_data' => [
                    'name' => $commande->getCmdLivNom(),
                    'images' => [$YOUR_DOMAIN],
                ],
            ],
            'quantity' => 1,
        ];
        Stripe::setApiKey('sk_test_51I8F6OGTYhnXIhU2xHLarwPX0VWMki5NexQZLb0fJXts1ytcfdIaHjFVThHvKVoTVcoXe4ummuZcT93GvTVuOkmh00tH15HUdG');
        $checkout_session = Session::create([
            // Récupère l'adresse mail du client pour éviter qu'il n'ait à le resaisir
            'customer_email'=>$this->getUser()->getCliEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [$produitStrip],
            'mode' => 'payment',
            // Redirection vers les vues correspondantes en cas de succès ou d'échec de la commande
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);
        $commande->setCmdStripIdSession($checkout_session->id);
        $entityManager->flush();
        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }
}
