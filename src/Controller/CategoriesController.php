<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/adminMode/categories')]
class CategoriesController extends AbstractController
{
    #[Route('/', name: 'app_categories_index', methods: ['GET'])]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('admin_mode/categories/index.html.twig', [
            'categories' => $categoriesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categories_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Categories();
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $today = new \DateTimeImmutable();
            $category->setCreatedAt($today)->setUpdatedAt($today)->setStatus('on');
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_categories_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_mode/categories/new.html.twig', [
            'category' => $category,
            'form' => $form,
            'errors' => $errors,
        ]);
    }



    #[Route('/{id}/edit', name: 'app_categories_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categories $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('app_categories_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_mode/categories/edit.html.twig', [
            'category' => $category,
            'form' => $form,
            'errors' => $errors,
        ]);
    }

    #[Route('/{id}', name: 'app_categories_delete', methods: ['POST'])]
    public function delete(Request $request, Categories $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categories_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/{id}', name: 'app_categories_status_edit', methods: ['GET'])]
    public function statusEdit(Categories $category, EntityManagerInterface $entityManager): Response
    {
        $category->setUpdatedAt(new \DateTimeImmutable());
        $category->setStatus($category->getStatus() === 'on' ? 'off' : 'on');
        $entityManager->flush();

        return $this->redirectToRoute('app_categories_index', [], Response::HTTP_SEE_OTHER);
    }
}
