<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Product;
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
        return $this->redirectToRoute('panier_index');
    }

}
