<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Chasse;
use App\Entity\Commande;
use App\Form\ChasseType;
use App\Repository\CategorieRepository;
use App\Repository\CommandeRepository;
use App\Repository\ProduitsRepository;
use App\Repository\SousCategorieRepository;
use App\Service\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/trouvailles', name: 'trouvailles')]
    #[Route('/filter/{param}/{id}', name: 'filter')]
    public function trouvailles(ProduitsRepository $produitsRepository, CategorieRepository $categorieRepository, SousCategorieRepository $sousCategorieRepository, $param = null, $id = null): Response
    {
        if ($param) {
            if ($param == 'categorie') {
                $categorie = $categorieRepository->find($id);
                $produits = $produitsRepository->findByCategory($categorie);
                $sousCategories = $sousCategorieRepository->findBy(['categorie' => $categorie]);
            }
            if ($param == 'sousCategorie') {
                $sousCategorie = $sousCategorieRepository->find($_POST['sousCategorie']);
                $produits = $produitsRepository->findBy(['sousCategorie' => $sousCategorie]);
                $sousCategories = $sousCategorieRepository->findBy(['categorie' => $sousCategorie->getCategorie()]);
            }

        } else {
            $sousCategories = $sousCategorieRepository->findAll();
            $produits = $produitsRepository->findAll();
        }

        $categories = $categorieRepository->findAll();


        return $this->render('home/trouvailles.html.twig', [
            'produits' => $produits,
            'categories' => $categories,
            'sousCategories' => $sousCategories

        ]);
    }

        #[Route('/', name: 'home')]
            public function home(): Response
            {


                return $this->render('home/home.html.twig', [

                ]);
            }


    #[Route('/ajoutPanier/{id}', name: 'ajoutPanier')]
    public function ajoutPanier(PanierService $panierService, $id): Response
    {
        $panierService->add($id);

        return $this->redirectToRoute('panier');
    }

    #[Route('/retraitPanier/{id}', name: 'retraitPanier')]
    public function retraitPanier(PanierService $panierService, $id): Response
    {
        $panierService->remove($id);


        return $this->redirectToRoute('panier');

    }


    #[Route('/supprimer/{id}', name: 'supprimer')]
    public function supprimer(PanierService $panierService, $id): Response
    {

        $panierService->delete($id);
        return $this->redirectToRoute('panier');
    }

    #[Route('/destroy', name: 'destroy')]
    public function destroy(PanierService $panierService): Response
    {
        $panierService->destroy();

        return $this->redirectToRoute('home');
    }

    #[Route('/panier', name: 'panier')]
    public function panier(PanierService $panierService): Response
    {
        $panier = $panierService->getFullPanier();
        $total = $panierService->getTotal();


        return $this->render('home/panier.html.twig', [
            'panier' => $panier,
            'total' => $total
        ]);
    }

    #[Route('/ajoutChasse', name: 'ajoutChasse')]
    public function ajoutChasse(\Symfony\Component\HttpFoundation\Request $request, EntityManagerInterface $manager): Response
    {
        $chasse = new Chasse();
        $form = $this->createForm(ChasseType::class, $chasse);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('photo')->getData();
            if ($photo) {

                $photo_bdd = date('YmdHis') . uniqid() . $photo->getClientOriginalName();

                $photo->move($this->getParameter('upload_directory'), $photo_bdd);

                $chasse->setPhoto($photo_bdd);

                $manager->persist($chasse);
                $manager->flush();

                $this->addFlash('success', 'Chasse ajoutée');
                return $this->redirectToRoute('gestionChasse');
            }
        }
        return $this->render('home/ajoutChasse.html.twig', [

            'form' => $form->createView()
        ]);
    }

        #[Route('/commande', name: 'commande')]
            public function commande(EntityManagerInterface $manager, PanierService $panierService): Response
            {
                $panier=$panierService->getFullPanier();
                $commande=new Commande();
                $commande->setUser($this->getUser());
                $commande->setDate(new \DateTime());

                foreach ($panier as $item){
                    $achat=new Achat();
                    $item['produit']->setVendu(1);
                    $manager->persist($item['produit']);
                    $achat->setProduit($item['produit']);
                    $achat->setCommande($commande);
                    $achat->setQuantite($item['quantite']);
                    $manager->persist($achat);
                }
                $panierService->destroy();
                $manager->persist($commande);
                $manager->flush();

                $this->addFlash('success', 'Merci pour votre commande');

                return $this->redirectToRoute('recapCommande');
            }

                #[Route('/recapCommande', name: 'recapCommande')]
                    public function recapCommande(CommandeRepository $commandeRepository):Response
                    {
                        $commandes=$commandeRepository->findBy(['user'=>$this->getUser()]);



                        return $this->render('home/recapCommande.html.twig', [
                            'commandes'=>$commandes
                        ]);
                    }


                #[Route('/nousContacter', name: 'nousContacter')]
                    public function nousContacter(): Response
                    {


                        return $this->render('home/nousContacter.html.twig', [

                        ]);
                    }
}
