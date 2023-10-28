<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{



    #[Route('/te', name: 'app_test')]
    public function index(): Response
    {
        $name = "bonjour 3A56";
        return $this->render('st/show.html.twig', [
            'namehtml' => $name
        ]);
    }
}