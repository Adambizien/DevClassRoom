<?php

namespace App\Controller;

use App\Repository\TutorialsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(TutorialsRepository $tutorialsRepository): Response
    {
        $tutorials = $tutorialsRepository->get3LastTutorialswithStatusOnAndWithImageName();
        return $this->render('home/index.html.twig', [
            'tutorials' => $tutorials,
        ]);
    }
}
