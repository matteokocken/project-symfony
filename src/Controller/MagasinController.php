<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/magasin', name: 'magasin')]
class MagasinController extends AbstractController
{
    #[Route('/list', name: '_list')]
    public function listAction(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $produitsRepo = $em->getRepository('App:Product');
        $produits = $produitsRepo->findAll();

        $args = array(
            'produits' => $produits
        );
        return $this->render('magasin/list.html.twig', $args);
    }

    #[Route('/ajoutendur', name: '_ajoutendur')]
    public function ajoutEnDurAction(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        $produit = new Product();
        $produit->setLibelle("Chaise de table - ADDE")
            ->setPrix(12)
            ->setEnStock(36);

        $em->persist($produit);
        $em->flush();

        return $this->redirectToRoute('magasin_list');
    }
}
