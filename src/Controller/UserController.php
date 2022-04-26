<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user')]

class UserController extends AbstractController
{
    #[Route('/display', name: '_display')]
    public function displayAction(EntityManagerInterface $em): Response
    {
        $userRepo = $em->getRepository('App:User');
        $users = $userRepo->findAll();

        $id_user = $this->getParameter('id_global');
        $userRepo = $em->getRepository("App:User");
        $ourUser = $userRepo->find($id_user);

        $args = array('users' => $users, 'ourUser' => $ourUser);

        return $this->render('user/index.html.twig', $args);
    }
    #[Route('/delete/{id}', name: '_delete')]
    public function index(User $user, EntityManagerInterface $em): Response
    {
        $id_user = $this->getParameter('id_global');
        $userRepo = $em->getRepository("App:User");
        $ourUser = $userRepo->find($id_user);

        if($user === $ourUser) { // On ne peux pas se supprimer
            $this->addFlash('info', "Vous ne pouvez pas supprimer votre compte");
        } else {

            //Vider son panier

            $em->remove($user);
            $em->flush();
        }
        return $this->redirectToRoute('user_display');
    }
}
