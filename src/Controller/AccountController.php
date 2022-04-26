<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CreateAccountType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/account', name: 'account')]
class AccountController extends AbstractController
{
    #[Route('/new', name: '_new')]
    public function newAccountAction(EntityManagerInterface $em, Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(CreateAccountType::class, $user);
        $form->add('send', SubmitType::class, ['label' => 'Créer un compte']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($user);
            $em->flush();
            $this->addFlash('info', 'Compte créé');
            return $this->redirectToRoute('accueil');
        }

        $args = array('myform' => $form->createView());
        return $this->render('account/create.html.twig', $args);

    }
    #[Route('/update', name: '_update')]
    public function updateAccountAction(EntityManagerInterface $em, Request $request): Response
    {
        //User id
        $id = $this->getParameter('id_global');
        $userRepo = $em->getRepository('App:User');
        $user = $userRepo->find($id);

        if(is_null($user))
            $this->redirectToRoute('accueil');

        $form = $this->createForm(CreateAccountType::class, $user);
        $form->add('send', SubmitType::class, ['label' => 'Modifier mon profil']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($user);
            $em->flush();
            $this->addFlash('info', 'Compte modifié');
            return $this->redirectToRoute('magasin_list');
        }

        $args = array('myform' => $form->createView());
        return $this->render('account/create.html.twig', $args);

    }

}
