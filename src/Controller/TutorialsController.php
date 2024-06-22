<?php

namespace App\Controller;

use App\Entity\Tutorials;
use App\Form\TutorialsType;
use App\Repository\ChapterRepository;
use App\Repository\TutorialsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ContentRepository;

#[Route('/tutorials')]
class TutorialsController extends AbstractController
{
    #[Route('/', name: 'app_tutorials_index', methods: ['GET'])]
    public function index(TutorialsRepository $tutorialsRepository): Response
    {
        if($this->getUser()->getRoles()[0] === 'ROLE_TEACHER'){
            $tutorial = $tutorialsRepository->findAllTutorialsWithAuthorEmail($this->getUser()->getEmail());
        }else{
            $tutorial = $tutorialsRepository->findAll();
        }
        return $this->render('admin_mode/tutorials/index.html.twig', [
            'tutorials' => $tutorial,
        ]);
    }

    #[Route('/new', name: 'app_tutorials_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tutorial = new Tutorials();
        $form = $this->createForm(TutorialsType::class, $tutorial);
        $form->handleRequest($request);
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $today = new \DateTimeImmutable();
            $tutorial->setAuthor($this->getUser()->getEmail());
            $tutorial->setCreatedAt($today)->setUpdatedAt($today)->setStatus('on');
            $entityManager->persist($tutorial);
            $entityManager->flush();

            return $this->redirectToRoute('app_tutorials_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_mode/tutorials/new.html.twig', [
            'tutorial' => $tutorial,
            'form' => $form,
            'errors' => $errors,
        ]);
    }

    #[Route('/show/{id}', name: 'app_tutorials_show', methods: ['GET'])]
    public function show(Tutorials $tutorial,ChapterRepository $chapterRepository,ContentRepository $contentRepository): Response
    {
        if($this->getUser()->getRoles()[0] === 'ROLE_TEACHER'){
            if($tutorial->getAuthor() !== $this->getUser()->getEmail()){
                return $this->redirectToRoute('app_tutorials_index', [], Response::HTTP_SEE_OTHER);
            }
        }
        $chapters = $chapterRepository->findAllChaptersByTutorialId($tutorial->getId());
        $contents = $contentRepository->findAllContentByTutorialId($tutorial->getId());

        usort($chapters, function($a, $b) {
            return $a->getStepOrder() <=> $b->getStepOrder();
        });

        usort($contents, function($a, $b) {
            return $a->getStepOrder() <=> $b->getStepOrder();
        });

        return $this->render('admin_mode/tutorials/show.html.twig', [
            'tutorial' => $tutorial,
            'chapters' => $chapters,
            'contents' => $contents,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tutorials_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tutorials $tutorial, EntityManagerInterface $entityManager): Response
    {

        if($this->getUser()->getRoles()[0] === 'ROLE_TEACHER'){
            if($tutorial->getAuthor() !== $this->getUser()->getEmail()){
                return $this->redirectToRoute('app_tutorials_index', [], Response::HTTP_SEE_OTHER);
            }
        }
        $form = $this->createForm(TutorialsType::class, $tutorial);
        $form->handleRequest($request);
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $tutorial->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('app_tutorials_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_mode/tutorials/edit.html.twig', [
            'tutorial' => $tutorial,
            'form' => $form,
            'errors' => $errors,
        ]);
    }

    #[Route('/{id}', name: 'app_tutorials_delete', methods: ['POST'])]
    public function delete(Request $request, Tutorials $tutorial, EntityManagerInterface $entityManager): Response
    {

        if($this->getUser()->getRoles()[0] === 'ROLE_TEACHER'){
            if($tutorial->getAuthor() !== $this->getUser()->getEmail()){
                return $this->redirectToRoute('app_tutorials_index', [], Response::HTTP_SEE_OTHER);
            }
        }
        if ($this->isCsrfTokenValid('delete'.$tutorial->getId(), $request->getPayload()->get('_token'))) {
            $chapters = $tutorial->getChapter();
            foreach ($chapters as $chapter) { 
                $contents = $chapter->getContent();
                foreach ($contents as $content) {
                    $entityManager->remove($content);
                }
                $entityManager->remove($chapter);
            }
            $entityManager->remove($tutorial);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tutorials_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/StatusEdit/{id}', name: 'app_tutorials_status_edit', methods: ['GET'])]
    public function statusEdit(Tutorials $tutorial, EntityManagerInterface $entityManager): Response
    {
        if($this->getUser()->getRoles()[0] === 'ROLE_TEACHER'){
            if($tutorial->getAuthor() !== $this->getUser()->getEmail()){
                return $this->redirectToRoute('app_tutorials_index', [], Response::HTTP_SEE_OTHER);
            }
        }
        $tutorial->setUpdatedAt(new \DateTimeImmutable());
        $status = $tutorial->getStatus() === 'on' ? 'off' : 'on';
        $tutorial->setStatus($status);
        $entityManager->flush();

        return $this->redirectToRoute('app_tutorials_index', [], Response::HTTP_SEE_OTHER);
    }

}
