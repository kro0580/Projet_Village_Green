<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ResetPassType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login", methods={"GET", "POST"})
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupération des erreurs de connexion
        $error = $authenticationUtils->getLastAuthenticationError();

        // Récupération du username du dernier utilisateur connecté
        $lastUsername = $authenticationUtils->getLastUsername();

        if($error){
            $this->addFlash('danger', 'Cet email n\'existe pas ou le mot de passe est erroné !');
            return $this->render('home/index.html.twig', [
                'error' => $error,
            ]);
        }
        else{
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
        }
    }

    /**
     * @Route("/forgot_password", name="app_forgotten_password")
     */
    public function forgottenPass(Request $request, ClientRepository $entityRepo, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, EntityManagerInterface $manager) {
        // Création du formulaire
        $form = $this->createForm(ResetPassType::class);

        // Traitement du formulaire
        $form->handleRequest($request);

        // Si le formulaire est valide
        if($form->isSubmitted() && $form->isValid()) {

            // On récupère les données
            $data = $form->getData();

            // On cherche si un utilisateur a cet email
            $user = $entityRepo->findOneBy(['cliEmail' => $data]);

            // Si l'utilisateur n'existe pas
            if(!$user) {

                // On envoie un message flash
                $this->addFlash('danger', 'Cette adresse mail n\'existe pas !');
                return $this->redirectToRoute('home');
            }

            // On génère un token
            $token = $tokenGenerator->generateToken();

            try {
                $user->setCliResetToken($token);
                $manager->persist($user);
                $manager->flush();
            }catch(\Exception $e) {
                $this->addFlash('warning', 'Une erreur est survenue : '. $e->getMessage());
                return $this->redirectToRoute('home');
            }

            // Envoi mail
            $mail = $user->getCliEmail();

            $email = (new TemplatedEmail())
                ->from('contact@village_green.org')
                ->to($mail)
                ->subject('Réinitialisation du mot de passe')
                ->htmlTemplate('emails/reset_password.html.twig')
                ->context([
                    'username' => $user->getCliPrenom(),
                    'token' => $user->getCliResetToken(),
                ]);

            $mailer->send($email);

            // On crée le message flash
            $this->addFlash('success', 'Un e-mail de réinitialisation du mot de passe vous a été envoyé');
            return $this->redirectToRoute('home');
        }

        // On envoie vers la page de demande de l'email
        return $this->render('security/forgotten_password.html.twig', [
            'emailForm' => $form->createView(),
            ]);
    }

    /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     */
    public function resetPassword($token, Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $manager) {
        // On cherche l'utilisateur avec le token fourni
        $user = $this->getDoctrine()->getRepository(Client::class)->findOneBy(['cli_reset_token' => $token]);

        if(!$user) {
            $this->addFlash('danger', 'Token inconnu');
            return $this->redirectToRoute('home');
        }

        // On vérifie si le formulaire est envoyé en méthode POST
        if($request->isMethod('POST')) {
            // On supprime le token
            $user->setCliResetToken("");

            // On crypte le mot de passe
            $user->setCliPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Mot de passe modifié avec succès');
            return $this->redirectToRoute('home');
        }
        else {
            return $this->render('security/reset_password.html.twig', [
                'token'=> $token,
            ]);
        }
    }
}

?>
