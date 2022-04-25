<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function indexAction(): Response
    {

        //$args = TODO: mettre le role de l'utilisateur

        return $this->render('accueil/index.html.twig');
    }
}
