<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Session\Session;

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
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $client->setCliRole('utilisateur');
            $client->setCliRegl('A définir');
            $client->setCliCateg('A définir');
            $client->setCliCoeff('1');
            $client->setCliPassword(
                $passwordEncoder->encodePassword(
                    $client,
                    $form->get('cliPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            // Envoi mail
            $mail = $client->getCliEmail();

            $email = (new TemplatedEmail())
                ->from('contact@village_green.org')
                ->to($mail)
                ->subject('Confirmation d\'inscription')
                ->htmlTemplate('emails/conf_inscription.html.twig')
                ->context([
                    'username' => $client->getCliPrenom(),
                ])
                ;

            $mailer->send($email);

            $this->addFlash(
                'success',
                'Un email de confirmation vous a été envoyé'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('client/new.html.twig', [
            'client' => $client,
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
        //Pour empêcher l'accès à un autre profil que celui de la personne connectée
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

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        $recup_password = $client->getCliPassword();

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'success',
                'Modification du profil validée !!'
            );
            return $this->redirectToRoute('home');
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
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
        if ($this->isCsrfTokenValid('delete'.$client->getCliId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            session_destroy();
            $entityManager->remove($client);
            $entityManager->flush();
        }

        // Destruction de la session
        $session = new Session();
        $session->invalidate();

        return $this->redirectToRoute('home');
    }
}