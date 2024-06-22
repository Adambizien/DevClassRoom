<?php

namespace App\Controller;

use App\Entity\Content;
use App\Form\ContentType;
use App\Repository\ChapterRepository;
use App\Repository\ContentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tutorial/{tutorialId}/chapter/{chapterId}/content')]

class ContentController extends AbstractController
{
    #[Route('/', name: 'app_content_index', methods: ['GET'])]
    public function index(ContentRepository $contentRepository): Response
    {
        return $this->render('admin_mode/content/index.html.twig', [
            'contents' => $contentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_content_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,ContentRepository $contentRepository,ChapterRepository $chapterRepository): Response
    {
        $content = new Content();
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);
        $tutorialId = $request->attributes->get('tutorialId');
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        if ($form->isSubmitted() && $form->isValid()) {

            if($form->get('imageFile')->getData()){
                $content->setContentType('image');
            }else if($form->get('text')->getData()){
                $content->setContentType('text');
            }else if($form->get('code')->getData()){
                $content->setContentType('code');
            }else if($form->get('video')->getData()){
                $content->setContentType('video');
            }else{
                $content->setContentType('unknown');
            }

            $today = new \DateTimeImmutable();
            $chapter = $chapterRepository->findChapterById($request->attributes->get('chapterId'));
            
            $content->setChapter($chapter);
            $content->setCreatedAt($today)->setUpdatedAt($today)->setStatus('on');
            $contentCount = $contentRepository->countContentByChapterId($request->attributes->get('chapterId'));
            $content->setStepOrder($contentCount + 1);
            $entityManager->persist($content);
            $entityManager->flush();

            return $this->redirectToRoute('app_tutorials_show', ['id' => $request->attributes->get('tutorialId')], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_mode/content/new.html.twig', [
            'content' => $content,
            'form' => $form,
            'tutorialId' => $tutorialId,
            'is_edit' => 'false',
            'type' => 'unknown',
            'errors' => $errors,
        ]);
    }

    #[Route('/{id}', name: 'app_content_show', methods: ['GET'])]
    public function show(Content $content): Response
    {
        return $this->render('admin_mode/content/show.html.twig', [
            'content' => $content,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_content_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Content $content, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);
        $tutorialId = $request->attributes->get('tutorialId');
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $content->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('app_tutorials_show', ['id' => $request->attributes->get('tutorialId')], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_mode/content/edit.html.twig', [
            'content' => $content,
            'form' => $form,
            'is_edit' => 'true',
            'tutorialId' => $tutorialId,
            'type' => $content->getContentType(),
            'errors' => $errors,
        ]);
    }

    #[Route('/{id}', name: 'app_content_delete', methods: ['POST'])]
    public function delete(Request $request, Content $content, EntityManagerInterface $entityManager,ContentRepository $contentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$content->getId(), $request->getPayload()->get('_token'))) {
            $stepOrderRemove = $content->getStepOrder();
            $entityManager->remove($content);
            $autherContents = $contentRepository->allContentByChapterId($request->attributes->get('chapterId'));
            foreach ($autherContents as $key => $value) {
                if($value->getStepOrder() > $stepOrderRemove){
                    $value->setStepOrder($value->getStepOrder() - 1);
                }
            }
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_tutorials_show', ['id' => $request->attributes->get('tutorialId')], Response::HTTP_SEE_OTHER);
    }

    #[Route('/StatusEdit/{id}', name: 'app_content_status_edit', methods: ['GET'])]
    public function statusEdit(Content $content, EntityManagerInterface $entityManager,Request $request): Response
    {
        
        $content->setUpdatedAt(new \DateTimeImmutable());
        $status = $content->getStatus() === 'on' ? 'off' : 'on';
        $content->setStatus($status);
        $entityManager->flush();

        return $this->redirectToRoute('app_tutorials_show', ['id' => $request->attributes->get('tutorialId')], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/reorder/{id}', name: 'app_content_update_order', methods: ['POST'])]
    public function reorder(Request $request, EntityManagerInterface $entityManager, ContentRepository $contentRepository, ChapterRepository $chapterRepository): Response
    {
        $content = $contentRepository->findContentById($request->attributes->get('id'));
        $stepOrder = $request->request->get('order');
        $newChapterId = $request->request->get('newChapterId');
        $isContentMoved = $request->request->get('isContentMoved');

        if ($isContentMoved) {
            $newChapter = $chapterRepository->find($newChapterId);
            if ($newChapter) {
                $content->setChapter($newChapter);
            }
        }
        
        $content->setStepOrder($stepOrder);
        $entityManager->flush();

        return $this->redirectToRoute('app_tutorials_show', ['id' => $request->attributes->get('tutorialId')], Response::HTTP_SEE_OTHER);
    }

    
}
