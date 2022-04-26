<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/panier', name: 'panier')]
class PanierController extends AbstractController
{
    #[Route('/', name: '_index')]
    public function indexAction(EntityManagerInterface $em): Response
    {
        //Voir mon panier
        $id_user = $this->getParameter('id_global');
        $userRepo = $em->getRepository("App:User");
        $user = $userRepo->find($id_user);

        $args = array('paniers' => $user->getPaniers()->getValues());

        return $this->render('panier/index.html.twig', $args);
    }
    #[Route('/add/', name: '_add')]
    public function addAction(EntityManagerInterface $em, Request $request): Response
    {
        $id_user = $this->getParameter('id_global');
        $quantity = $request->request->get("quantity");

        $userRepo = $em->getRepository("App:User");
        $user = $userRepo->find($id_user);

        foreach($quantity as $id => $nb) {
            if($nb >= 1) {
                $productRepo = $em->getRepository('App:Product');
                $product = $productRepo->find($id);

                $panier = new Panier();
                $panier->setQuantity(intval($nb))
                    ->setUser($user)
                    ->setProduct($product);

                //Voir si le produit n'est pas déja dans la bdd
                $userPaniers = $user->getPaniers()->getValues();
                foreach($userPaniers as $userPanier) {
                    // Si l'utilisateur à déjà ce produit dans la table panier
                    // alors ajouter la nouvelle quantity à celle déjà présente dans la bdd
                    if($userPanier->getProduct() === $product) {
                        $panier = $userPanier;
                        $panier->setQuantity($panier->getQuantity() + $nb);
                    }
                }
                $em->persist($panier); // Pour le panier c'est bon

                // Supprimer la quantité qui a été ajouté au panier à celle du produit
                $productQuantity = $product->getEnStock();
                $product->setEnStock($productQuantity - $nb);

                $em->persist($product);
            }
        }
        $em->flush();
        return $this->redirectToRoute('magasin_list');
    }

    #[Route('/delete/{id_product}',
        name: '_delete',
        requirements: ['id_product' => "[1-9][0-9]*"]
    )]
    public function deleteAction(Product $id_product, EntityManagerInterface $em): Response
    {
        // $id_product viens de Panier
        // $product viens de Product

        //Recupere l'user
        $id_user = $this->getParameter('id_global');
        $userRepo = $em->getRepository("App:User");
        $user = $userRepo->find($id_user);

        $panierRepo = $em->getRepository("App:Panier");

        $panierRepo = $em->getRepository('App:Panier');
        $panier = $panierRepo->findOneBy(['user' => $user, 'product' => $id_product]);

        //Quantity dans le panier
        $panierQuantity = $panier->getQuantity();

        // Quantity de base
        $produitQuantity = $id_product->getEnStock();

        $id_product->setEnStock($produitQuantity + $panierQuantity);

        $em->remove($panier);
        $em->persist($id_product);
        $em->flush();
        return $this->redirectToRoute('panier_index');
    }

    #[Route('/clear', name: '_clear',
    )]
    public function clearAction(EntityManagerInterface $em): Response
    {
        //Recupere l'user
        $id_user = $this->getParameter('id_global');
        $userRepo = $em->getRepository("App:User");
        $user = $userRepo->find($id_user);

        $panierRepo = $em->getRepository('App:Panier');
        $paniers = $panierRepo->findBy(['user' => $user]);
        foreach($paniers as $panier) {
            //On recuperer le produit
            $product = $panier->getProduct();

            //On modifie la valeur en stock du produit
            $quantityProduct = $product->getEnStock();
            $product->setEnStock($quantityProduct + $panier->getQuantity());
            $em->remove($panier);
            $em->persist($product);
        }
        $em->persist($paniers);
        $em->flush();
        return $this->redirectToRoute('panier_index');
    }

    #[Route('/buy/', name: '_buy')]
    public function buyAction(EntityManagerInterface $em): Response
    {
        // De meme que clear action sauf qu'on remet pas les quantités à jour
        $id_user = $this->getParameter('id_global');
        $userRepo = $em->getRepository("App:User");
        $user = $userRepo->find($id_user);

        $panierRepo = $em->getRepository('App:Panier');
        $paniers = $panierRepo->findBy(['user' => $user]);

        foreach($paniers as $panier) {
            $em->remove($panier);
        }
        $em->flush();
        return $this->redirectToRoute('panier_index');
    }

    public function menuAction(EntityManagerInterface $em): Response
    {
        $id_user = $this->getParameter('id_global');
        $userRepo = $em->getRepository("App:User");
        $user = $userRepo->find($id_user);

        $panierRepo = $em->getRepository('App:Panier');
        $paniers = $panierRepo->findBy(['user' => $user]);


        $args = array('nb_articles' => sizeof($paniers));
        return $this->render('Elements/menu.html.twig', $args);
    }
}
