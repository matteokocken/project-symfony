<?php

namespace App\Controller;

use App\Entity\UserSecure;
use App\Form\CreateAccountType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(path: '/security', name: 'security')]
class SecurityController extends AbstractController
{
    #[Route('/new', name: '_new')]
    public function newAccountAction(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new UserSecure();
        $form = $this->createForm(UserType::class, $user);
        $form->add('send', SubmitType::class, ['label' => 'Créer un compte']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);

            $em->persist($user);
            $em->flush();
            $this->addFlash('info', 'Compte créé');
            return $this->redirectToRoute('accueil');
        }

        $args = array('myform' => $form->createView());
        return $this->render('account/create.html.twig', $args);
    }

    #[Route('/update', name: '_update'),
        Security("is_granted('IS_AUTHENTICATED_FULLY')")
    ]
    public function updateAccountAction(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->add('send', SubmitType::class, ['label' => 'Modifier mon profil']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);

            $em->persist($user);
            $em->flush();
            $this->addFlash('info', 'Compte modifié');
            if($user->getRoles()[0] === 'ROLE_SUPER_ADMIN')
                return $this->redirectToRoute('accueil');
            return $this->redirectToRoute('magasin_list');
        }

        $args = array('myform' => $form->createView());
        return $this->render('account/create.html.twig', $args);

    }
    #[Route(path: '/login', name: '_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) { // Si déjà connecté
             return $this->redirectToRoute('accueil');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: '_logout')]
    public function logout(): void
    {
        $this->addFlash('info', 'Vous avez été déconnecté');
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/addadmin', name: '_addadmin'),
    IsGranted("ROLE_SUPER_ADMIN")]
    public function newAdminAccountAction(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new UserSecure("ROLE_ADMIN");
        $form = $this->createForm(UserType::class, $user);
        $form->add('send', SubmitType::class, ['label' => 'Créer un compte Admin']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);

            $em->persist($user);
            $em->flush();
            $this->addFlash('info', 'Compte créé');
            return $this->redirectToRoute('user_display');
        }

        $args = array('myform' => $form->createView());
        return $this->render('account/create.html.twig', $args);
    }
}
