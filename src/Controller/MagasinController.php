<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/magasin', name: 'magasin'),
    IsGranted('ROLE_CLIENT')
]
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

    #[Route('/add', name: '_add'),
        IsGranted('ROLE_ADMIN')
    ]
    public function addAction(EntityManagerInterface $em, Request $request): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->add('send', SubmitType::class, ['label' => 'Ajouter un produit']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();
            $this->addFlash('info', 'Produit créé !');
            return $this->redirectToRoute("accueil");
        }

        $args = array('myform' => $form->createView());
        return $this->render('magasin/add.html.twig', $args);
    }

}
