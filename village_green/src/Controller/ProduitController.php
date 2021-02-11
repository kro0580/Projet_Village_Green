<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="produit_index", methods={"GET"})
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $produits = $this->getDoctrine()
            ->getRepository(Produit::class)
            ->findAll();

//        $produits = $paginator->paginate(
//            $donnees, // Requête contenant les données à paginer (ici nos produits)
//            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
//            15 // Nombre de résultats par page
//        );

        return $this->render('produit/index.html.twig', [
            'produits' => $produits
        ]);
    }

    /**
     * @Route("/new", name="produit_new", methods={"GET","POST"})
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération de la saisi sur l'upload
            $pictureFile = $form['proPhoto']->getData();
            // Vérification s'il y a un upload photo
            if ($pictureFile)
            {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

                // Assignation de la valeur à la propriété picture à l'aide du setter
                $produit->setProPhoto($newFilename);
                try
                {
                    // Déplacement du fichier vers le répertoire de destination sur le serveur
                    $pictureFile->move(
                        $this->getParameter('photo_directory'),
                        $newFilename
                    );
                } catch (FileException $e)
                {
                    // Gestion de l'erreur si le déplacement ne s'est pas effectué
                }
            }
            // Si le formulaire est soumis et valide, alors nous allons utiliser l'objet EntityManager de Doctrine. Il nous permet d'envoyer et d'aller chercher des objets dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            //Ensuite nous allons persister notre entité, c'est-à-dire que nous allons la préparer à la sauvegarde des données saisies
            $entityManager->persist($produit);
            // Enfin, pour envoyer les données dans la base, nous utilisons la méthode flush()
            $entityManager->flush();

            // Message de succès de modification du produit
            $this->addFlash(
                'success',
                'Produit ajouté avec succès !!'
            );

            // Et nous redirigeons vers la liste des produits
            return $this->redirectToRoute('produit_index');
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{proId}", name="produit_show", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("/{proId}/edit", name="produit_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Produit $produit
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function edit(Request $request, Produit $produit, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(!empty($form['proPhoto'])){
                // Récupération de la saisi sur l'upload
                $pictureFile = $form['proPhoto']->getData();
                // Vérification s'il y a un upload photo
                    if ($pictureFile)
                    {
                        $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

                        // Assignation de la valeur à la propriété picture à l'aide du setter
                        $produit->setProPhoto($newFilename);
                            try
                            {
                                // Déplacement du fichier vers le répertoire de destination sur le serveur
                                $pictureFile->move(
                                    $this->getParameter('photo_directory'),
                                    $newFilename
                                );
                            } catch (FileException $e)
                            {
                                // Gestion de l'erreur si le déplacement ne s'est pas effectué
                            }
                    }
            }
            // Si le formulaire est soumis et valide, alors nous allons utiliser l'objet EntityManager de Doctrine. Il nous permet d'envoyer et d'aller chercher des objets dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            //Ensuite nous allons persister notre entité, c'est-à-dire que nous allons la préparer à la sauvegarde des données saisies
            $entityManager->persist($produit);
            // Enfin, pour envoyer les données dans la base, nous utilisons la méthode flush()
            $entityManager->flush();

            // Message de succès d'ajout du produit
            $this->addFlash(
                'success',
                'Produit modifié avec succès !!'
            );

            return $this->redirectToRoute('produit_index');
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{proId}", name="produit_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Produit $produit): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getProId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        // Message de succès de suppression du produit
        $this->addFlash(
            'success',
            'Produit supprimé avec succès !!'
        );

        return $this->redirectToRoute('produit_index');
    }
}
