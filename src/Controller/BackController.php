<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Chasse;
use App\Entity\Produits;
use App\Entity\SousCategorie;
use App\Form\CategorieType;
use App\Form\ChasseType;
use App\Form\EditChasseType;
use App\Form\EditProduitsType;
use App\Form\ProduitsType;
use App\Form\SousCategorieType;
use App\Repository\CategorieRepository;
use App\Repository\ChasseRepository;
use App\Repository\ProduitsRepository;
use App\Repository\SousCategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
class BackController extends AbstractController
{
    #[Route('/back', name: 'app_back')]
    public function index(): Response
    {
        return $this->render('back/index.html.twig', [
            'controller_name' => 'BackController',
        ]);
    }


    #[Route('/ajoutSousCategorie', name: 'ajoutSousCategorie')]
    public function ajoutSousCategorie(Request $request, EntityManagerInterface $manager): Response
    {
        $sousCategorie = new SousCategorie();
        $form = $this->createForm(SousCategorieType::class, $sousCategorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($sousCategorie);
            $manager->flush();

            $this->addFlash('success', 'Sous-Catégorie ajoutée');
            return $this->redirectToRoute('gestionSousCategorie');
        }
        return $this->render('back/ajoutSousCategorie.html.twig', [

            'form' => $form->createView()
        ]);
    }

    #[Route('/editSousCategorie/{id}', name: 'editSousCategorie')]
    public function editSousCategorie(Request $request, SousCategorieRepository $repository, EntityManagerInterface $manager, $id): Response
    {
        $sousCategorie = $repository->find($id);
        $form = $this->createForm(SousCategorieType::class, $sousCategorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($sousCategorie);
            $manager->flush();
            $this->addFlash('success', 'Sous-Catégorie modifiée');
            return $this->redirectToRoute('gestionSousCategorie');
        }
        return $this->render('back/editSousCategorie.html.twig', [

            'form' => $form->createView()
        ]);
    }

    #[Route('/gestionSousCategorie', name: 'gestionSousCategorie')]
    public function gestionSousCategorie(SousCategorieRepository $sousCategorieRepository): Response
    {
        $sousCategorie = $sousCategorieRepository->findAll();

        return $this->render('back/gestionSousCategorie.html.twig', [

            'sousCategories' => $sousCategorie

        ]);
    }

    #[Route('/deleteSousCategorie/{id}', name: 'deleteSousCategorie')]
    public function deleteSousCategorie(SousCategorieRepository $repository, EntityManagerInterface $manager, $id): Response
    {
        $sup = $repository->find($id);

        $manager->remove($sup);
        $manager->flush();


        return $this->redirectToRoute('gestionSousCategorie', [

        ]);
    }

    #[Route('/ajoutCategorie', name: 'ajoutCategorie')]
    public function ajoutCategorie(Request $request, EntityManagerInterface $manager): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($categorie);
            $manager->flush();

            $this->addFlash('success', 'Catégorie ajoutée');
            return $this->redirectToRoute('gestionCategorie');
        }
        return $this->render('back/ajoutCategorie.html.twig', [

            'form' => $form->createView()
        ]);
    }


    #[Route('/editCategorie/{id}', name: 'editCategorie')]
    public function editCategorie(Request $request, CategorieRepository $repository, EntityManagerInterface $manager, $id): Response
    {
        $categorie = $repository->find($id);
        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($categorie);
            $manager->flush();
            $this->addFlash('success', 'Catégorie modifiée');
            return $this->redirectToRoute('gestionCategorie');
        }
        return $this->render('back/editCategorie.html.twig', [

            'form' => $form->createView()
        ]);

    }

    #[Route('/gestionCategorie', name: 'gestionCategorie')]
    public function gestionCategorie(CategorieRepository $categorieRepository): Response
    {
        $categorie = $categorieRepository->findAll();

        return $this->render('back/gestionCategorie.html.twig', [

            'categories' => $categorie

        ]);

    }

    #[Route('/deleteCategorie/{id}', name: 'deleteCategorie')]
    public function deleteCategorie(CategorieRepository $repository, EntityManagerInterface $manager, $id): Response
    {
        $sup = $repository->find($id);

        $manager->remove($sup);
        $manager->flush();


        return $this->redirectToRoute('gestionCategorie', [

        ]);
    }

    #[Route('/ajoutProduits', name: 'ajoutProduits')]
    public function ajoutProduits(Request $request, EntityManagerInterface $manager): Response
    {
        $produit = new Produits();
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();
            $produit->setVendu(0);
            if ($photo) {

                $photo_bdd = date('YmdHis') . uniqid() . $photo->getClientOriginalName();

                $photo->move($this->getParameter('upload_directory'), $photo_bdd);

                $produit->setPhoto($photo_bdd);
                $manager->persist($produit);
                $manager->flush();

                $this->addFlash('success', 'Produit ajouté');
                return $this->redirectToRoute('gestionProduits');
            }

        }

        return $this->render('back/ajoutProduits.html.twig', [
            'form' => $form->createView()

        ]);
    }


    #[Route('/gestionProduits', name: 'gestionProduits')]
    public function gestionProduits(ProduitsRepository $produitsRepository): Response

    {
        $produits = $produitsRepository->findAll();


        return $this->render('back/gestionProduits.html.twig', [
            'produits' => $produits
        ]);
    }

    #[Route('/editProduits/{id}', name: 'editProduits')]
    public function editProduits(Produits $produits, Request $request, EntityManagerInterface $manager): Response
    {


        $form = $this->createForm(EditProduitsType::class, $produits);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('editPhoto')->getData()) {
                $photo = $form->get('editPhoto')->getData();
                $photo_bdd = date('YmdHis') . uniqid() . $photo->getClientOriginalName();

                $photo->move($this->getParameter('upload_directory'), $photo_bdd);
                unlink($this->getParameter('upload_directory') . '/' . $produits->getPhoto());
                $produits->setPhoto($photo_bdd);
            }
            $manager->persist($produits);
            $manager->flush();

            $this->addFlash('success', 'Produit modifié');
            return $this->redirectToRoute('gestionProduits');

        }

        return $this->render('back/editProduits.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/deleteProduits/{id}', name: 'deleteProduits')]
    public function deleteProduits(Produits $produits, EntityManagerInterface $manager): Response
    {
        $manager->remove($produits);
        $manager->flush();

        $this->addFlash('success', 'Produits supprimé !!! ');


        return $this->redirectToRoute('gestionProduits');
    }


    #[Route('/editChasse/{id}', name: 'editChasse')]
    public function editChasse(Request $request, Chasse $chasse, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(EditChasseType::class, $chasse);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('editPhoto')->getData()) {
                $photo = $form->get('editPhoto')->getData();
                $photo_bdd = date('YmdHis') . uniqid() .
                    $photo->getClientOriginalName();

                $photo->move($this->getParameter('upload_directory'), $photo_bdd);
                unlink($this->getParameter('upload_directory') . '/' . $chasse->getPhoto());
                $chasse->setPhoto($photo_bdd);
            }
            $manager->persist($chasse);
            $manager->flush();
            $this->addFlash('success', 'Chasse modifiée');
            return $this->redirectToRoute('gestionChasse');
        }
        return $this->render('back/editChasse.html.twig', [

            'form' => $form->createView()
        ]);
    }

    #[Route('/gestionChasse', name: 'gestionChasse')]
    public function gestionChasse(ChasseRepository $chasseRepository): Response

    {
        $chasses = $chasseRepository->findAll();


        return $this->render('back/gestionChasse.html.twig', [
            'chasses' => $chasses
        ]);
    }

    #[Route('/deleteChasse/{id}', name: 'deleteChasse')]
    public function deleteChasse(Chasse $chasse, EntityManagerInterface $manager): Response
    {
        $manager->remove($chasse);
        $manager->flush();

        $this->addFlash('success', 'Chasse supprimée !!! ');


        return $this->redirectToRoute('gestionChasse');
    }

}// Fermeture de controller
