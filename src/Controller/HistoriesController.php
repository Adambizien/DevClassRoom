<?php

namespace App\Controller;

use App\Entity\Histories;
use App\Form\HistoriesType;
use App\Repository\HistoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/histories')]
class HistoriesController extends AbstractController
{
    #[Route('/', name: 'app_histories_index', methods: ['GET'])]
    public function index(HistoriesRepository $historiesRepository): Response
    {
        return $this->render('histories/index.html.twig', [
            'histories' => $historiesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_histories_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $history = new Histories();
        $form = $this->createForm(HistoriesType::class, $history);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($history);
            $entityManager->flush();

            return $this->redirectToRoute('app_histories_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('histories/new.html.twig', [
            'history' => $history,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_histories_show', methods: ['GET'])]
    public function show(Histories $history): Response
    {
        return $this->render('histories/show.html.twig', [
            'history' => $history,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_histories_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Histories $history, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HistoriesType::class, $history);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_histories_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('histories/edit.html.twig', [
            'history' => $history,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_histories_delete', methods: ['POST'])]
    public function delete(Request $request, Histories $history, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$history->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($history);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_histories_index', [], Response::HTTP_SEE_OTHER);
    }
}
