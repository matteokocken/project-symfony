<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserSecure;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user'), IsGranted("ROLE_ADMIN")]

class UserController extends AbstractController
{
    #[Route('/display', name: '_display')]
    public function displayAction(EntityManagerInterface $em): Response
    {
        $userRepo = $em->getRepository('App:UserSecure');
        $users = $userRepo->findAll();

//        $id_user = $this->getParameter('id_global');
//        $userRepo = $em->getRepository("App:User");
//        $ourUser = $userRepo->find($id_user);
        $ourUser = $this->getUser();

        $args = array('users' => $users, 'ourUser' => $ourUser);

        return $this->render('user/index.html.twig', $args);
    }
    #[Route('/delete/{id}', name: '_delete')]
    public function index(UserSecure $user, EntityManagerInterface $em): Response
    {
//        $id_user = $this->getParameter('id_global');
//        $userRepo = $em->getRepository("App:User");
//        $ourUser = $userRepo->find($id_user);
        $ourUser = $this->getUser();


        if($user === $ourUser) { // On ne peux pas se supprimer
            $this->addFlash('info', "Vous ne pouvez pas supprimer votre compte");
        } else {

            //Pas super admin
            if($user->getRoles()[0] === 'ROLE_SUPER_ADMIN') {
                $this->addFlash('info', "Vous ne pouvez pas supprimer un super admin");
            } else {
                //Vider son panier
                $this->addFlash('info', "L'utilisateur à été supprimé");
                $em->remove($user);
                $em->flush();
            }
        }
        return $this->redirectToRoute('user_display');
    }
}
