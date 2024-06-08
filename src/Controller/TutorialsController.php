<?php

namespace App\Controller;

use App\Entity\Tutorials;
use App\Form\TutorialsType;
use App\Repository\TutorialsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tutorials')]
class TutorialsController extends AbstractController
{
    #[Route('/', name: 'app_tutorials_index', methods: ['GET'])]
    public function index(TutorialsRepository $tutorialsRepository): Response
    {
        return $this->render('tutorials/index.html.twig', [
            'tutorials' => $tutorialsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tutorials_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tutorial = new Tutorials();
        $form = $this->createForm(TutorialsType::class, $tutorial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tutorial);
            $entityManager->flush();

            return $this->redirectToRoute('app_tutorials_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tutorials/new.html.twig', [
            'tutorial' => $tutorial,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tutorials_show', methods: ['GET'])]
    public function show(Tutorials $tutorial): Response
    {
        return $this->render('tutorials/show.html.twig', [
            'tutorial' => $tutorial,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tutorials_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tutorials $tutorial, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TutorialsType::class, $tutorial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tutorials_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tutorials/edit.html.twig', [
            'tutorial' => $tutorial,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tutorials_delete', methods: ['POST'])]
    public function delete(Request $request, Tutorials $tutorial, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tutorial->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($tutorial);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tutorials_index', [], Response::HTTP_SEE_OTHER);
    }
}
