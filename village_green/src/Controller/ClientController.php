<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Adresse;
use App\Form\ClientType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/client")
 */
class ClientController extends AbstractController
{
    /**
     * @Route("/", name="client_index", methods={"GET"})
     */
    public function index(): Response
    {
        // On instancie Doctrine et on récupère l'ensemble des clients
        $client = $this->getDoctrine()
            ->getRepository(Client::class)
            ->findAll();

        return $this->render('client/index.html.twig', [
            'clients' => $client,
        ]);
    }

    /**
     * @Route("/new", name="client_new", methods={"GET","POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer): Response
    {
        // On instancie l'entité Client
        $client = new Client();

        // On créé l'objet formulaire qui prend comme paramètres le type et les données à envoyer
        $form = $this->createForm(ClientType::class, $client);

        // On récupère les données saisies
        $form->handleRequest($request);

        // On vérifie si le formulaire a été envoyé et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // Ici le formulaire a été envoyé et les données sont valides
            $client->setCliRole('utilisateur');
            $client->setCliRegl('A définir');
            $client->setCliCateg('A définir');
            $client->setCliCoeff('1');
            // Encodage du mot de passe
            $password_hash = $passwordEncoder->encodePassword($client, $client->getCliPassword());
            $client->setCliPassword($password_hash);

            // Si le formulaire est soumis et valide, alors nous allons utiliser l'objet EntityManager de Doctrine. Il nous permet d'envoyer et d'aller chercher des objets dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            // On met en place toutes les données dans l'objet
            $entityManager->persist($client);
            // On écrit dans la BDD
            $entityManager->flush();

            // Envoi mail avec MailHog via MailerInterface
            // On récupère l'adresse mail du client
            $mail = $client->getCliEmail();

            // On crée un template du mail
            $email = (new TemplatedEmail())
                ->from('contact@village_green.org')
                ->to($mail)
                ->subject('Confirmation d\'inscription')
                ->htmlTemplate('emails/conf_inscription.html.twig')
                ->context([
                    'username' => $client->getCliPrenom(),
                ])
                ;

            // Envoi du mail
            $mailer->send($email);

            // Message de confirmation de l'envoi du mail
            $this->addFlash(
                'success',
                'Un email de confirmation vous a été envoyé'
            );

            return $this->redirectToRoute('home');
        }

        // Affichage de la page d'inscription où on envoie les valeurs dans la vue
        return $this->render('client/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{cliId}", name="client_show", methods={"GET"})
     * @param Client $client
     * @return Response
     */
    public function show(Client $client): Response
    {
        // Pour empêcher l'accès à un autre profil que celui de la personne connectée
        $this->denyAccessUnlessGranted('show', $client, 'Vous ne pouvez pas accéder à ce profil !');

        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    /**
     * @Route("/{cliId}/edit", name="client_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Client $client
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function edit(Request $request, Client $client, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        //Pour empêcher la modification d'un autre profil que celui de la personne connectée
        $this->denyAccessUnlessGranted('edit', $client, 'Vous ne pouvez pas modifier ce profil !');

        // On créé l'objet formulaire qui prend comme paramètres le type et les données à envoyer
        $form = $this->createForm(ClientType::class, $client);
        // On récupère les données saisies
        $form->handleRequest($request);
        // On récupère le mot de passe (l'input password est caché dans le formulaire)
        $recup_password = $client->getCliPassword();
        // On vérifie si le formulaire a été envoyé et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // Si le formulaire est soumis et valide, alors nous allons utiliser l'objet EntityManager de Doctrine. Il nous permet d'envoyer et d'aller chercher des objets dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            // On écrit dans la BDD
            $entityManager->flush();
            // Message flash
            $this->addFlash(
                'success',
                'Modification du profil validée !!'
            );
            return $this->redirectToRoute('home');
        }
        // Affichage de la page de modification
        return $this->render('client/edit.html.twig', [
            'form' => $form->createView(),
            'recup' => $recup_password,
        ]);
    }

    /**
     * @Route("/{cliId}", name="client_delete", methods={"DELETE"})
     * @param Request $request
     * @param Client $client
     * @return Response
     */
    public function delete(Request $request, Client $client): Response
    {
        // On récupère les adresses enregistrées par le client et on les compte
        $adressesNombre= $client->getCliAdresses()->count();
        // Nous allons utiliser l'objet EntityManager de Doctrine. Il nous permet d'envoyer et d'aller chercher des objets dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        // On récupère l'ID et les adresses enregistrées par le client
        if ($this->isCsrfTokenValid('delete'.$client->getCliId(), $request->request->get('_token')) && $adressesNombre == 0) {
            // Destruction de la session
            session_destroy();
            // On supprime le client
            $entityManager->remove($client);
            // On met à jour la BDD
            $entityManager->flush();
        } else {
            // Si il y a une adresse, on récupère les données
            $adresses=$client->getCliAdresses()->getValues();
            // On parcours les adresses
            foreach ($adresses as $adresse){
                // On récupère l'ID de l'adresse
                $adresse= $entityManager->getRepository(Adresse::class)->find($adresse->getId());
                // On la supprime
                $entityManager->remove($adresse);
                // On met à jour la BDD
                $entityManager->flush();
            }
            // On supprime le client
            $entityManager->remove($client);
            // On met à jour la BDD
            $entityManager->flush();
             }

        // Destruction de la session
        $session = new Session();
        $session->invalidate();

        return $this->redirectToRoute('home');
    }
}