<?php

namespace App\Controller;
use App\Service\CalculMoyenne;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function indexAction(CalculMoyenne $moyenne): Response
    {

        $user = $this->getUser();

        //Utilisation du service
        $note = [12, 15, 20, 16, 8]; //Doit retourner 12 + 15 + ... + = 71
        $result = $moyenne->moyenne($note);
        $args = [
            'connected' => $user !== null,
            'user' => $user,
            'result' => $result
        ];

        return $this->render('accueil/index.html.twig', $args);
    }
}
