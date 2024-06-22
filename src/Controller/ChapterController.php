<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Form\ChapterType;
use App\Repository\ChapterRepository;
use App\Repository\TutorialsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tutorial/{tutorialId}/chapter')]
class ChapterController extends AbstractController
{
    #[Route('/new', name: 'app_chapter_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,TutorialsRepository $tutorialsRepository,ChapterRepository $chapterRepository): Response
    {
        $chapter = new Chapter();
        $form = $this->createForm(ChapterType::class, $chapter);
        $form->handleRequest($request);
        $tutorialId = $request->attributes->get('tutorialId');
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        

        if ($form->isSubmitted() && $form->isValid()) {
            $today = new \DateTimeImmutable();
            $tutorial = $tutorialsRepository->findTutorialById($tutorialId);
            $chapter->setTutorials($tutorial);
            $chapter->setCreatedAt($today)->setUpdatedAt($today)->setStatus('on');
            $chapterCount = $chapterRepository->countChaptersByTutorialId($tutorialId);
            $chapter->setStepOrder($chapterCount + 1);
            $entityManager->persist($chapter);
            $entityManager->flush();
            return $this->redirectToRoute('app_tutorials_show', ['id' => $tutorialId], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_mode/chapter/new.html.twig', [
            'chapter' => $chapter,
            'form' => $form,
            'tutorialId' => $tutorialId,
            'errors' => $errors,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_chapter_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chapter $chapter, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChapterType::class, $chapter);
        $form->handleRequest($request);
        $tutorialId = $request->attributes->get('tutorialId');
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tutorials_show', ['id' => $tutorialId], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_mode/chapter/edit.html.twig', [
            'chapter' => $chapter,
            'form' => $form,
            'tutorialId' => $tutorialId,
            'errors' => $errors,
        ]);
    }

    #[Route('/{id}', name: 'app_chapter_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $entityManager,ChapterRepository $chapterRepository): Response
    {
        $chapter = $chapterRepository->findChapterById($request->attributes->get('id'));
        $tutorialId = $request->attributes->get('tutorialId');
        if ($this->isCsrfTokenValid('delete'.$chapter->getId(), $request->getPayload()->get('_token'))) {
            $stepOrderRemove = $chapter->getStepOrder();
            $contents = $chapter->getContent();
    
            foreach ($contents as $content) {
                $entityManager->remove($content);
            }
            $entityManager->remove($chapter);

            $chapters = $chapterRepository->findAllChaptersByTutorialId($tutorialId);
            foreach ($chapters as $chapter) {
                if ($chapter->getStepOrder() > $stepOrderRemove) {
                    $chapter->setStepOrder($chapter->getStepOrder() - 1);
                }
            }
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tutorials_show', [
            'id' => $tutorialId,
        ], Response::HTTP_SEE_OTHER);
    }

    #[Route('/StatusEdit/{id}', name: 'app_chapter_status_edit', methods: ['GET'])]
    public function statusEdit(Chapter $chapter, EntityManagerInterface $entityManager,Request $request): Response
    {   
        $chapter->setUpdatedAt(new \DateTimeImmutable());
        $status = $chapter->getStatus() === 'on' ? 'off' : 'on';
        $chapter->setStatus($status);
        $entityManager->flush();
        return $this->redirectToRoute('app_tutorials_show', ['id' => $request->attributes->get('tutorialId')], Response::HTTP_SEE_OTHER);
    }

    #[Route('/reorder/{id}', name: 'app_chapter_update_order', methods: ['POST'])]
    public function reorder(Request $request, EntityManagerInterface $entityManager,ChapterRepository $chapterRepository): Response
    {
        $tutorialId = $request->attributes->get('tutorialId');
        $chapterId = $request->attributes->get('id');
        $chapter = $chapterRepository->findChapterById($chapterId);
        $stepOrder = $request->request->get('order');
        $chapter->setStepOrder($stepOrder);
        $entityManager->flush();
        return $this->redirectToRoute('app_tutorials_show', ['id' => $tutorialId], Response::HTTP_SEE_OTHER);
    }
}
