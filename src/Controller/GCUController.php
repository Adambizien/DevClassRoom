<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GCUController extends AbstractController
{
    #[Route('/gcu', name: 'app_gcu')]
    public function index(): Response
    {
        return $this->render('gcu/index.html.twig', [
        ]);
    }
}
