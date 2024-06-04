<?php

namespace App\Controller;

use App\Form\EditEmailType;
use App\Form\EditPasswordType;
use App\Form\EditPersonalInfoType;
use App\Form\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class ProfileController extends AbstractController
{ 
    #[Route('/profile', name: 'profile')]
    
    public function profile(): Response
    {
       $user = $this->getUser();

       return $this->render('profile/index.html.twig', [
           'user' => $user,
       ]);
    }

    #[Route('/profile/edit', name: 'profile_edit')]

    public function editProfile(Request $request,EntityManagerInterface $entityManager): Response
    {
       $user = $this->getUser();
       $form = $this->createForm(EditPersonalInfoType::class, $user);

       $form->handleRequest($request);
       $errors = [];
        $formErrors = $form->getErrors(true, true);

       
        if($form->isSubmitted()){
            foreach ($formErrors as $error) {
                $errors[] = $error->getMessage();
            }
        }

       if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->persist($user);
            $entityManager->flush();
           return $this->redirectToRoute('profile');
       }

       return $this->render('profile/edit_profile.html.twig', [
           'form' => $form->createView(),
            'errors' => $errors
       ]);
    }

    #[Route('/profile/edit-email', name: 'profile_edit_email')]
    
   public function editEmail(Request $request,EntityManagerInterface $entityManager): Response
    {
       $user = $this->getUser();
       $form = $this->createForm(EditEmailType::class, $user);

       $form->handleRequest($request);
       $errors = [];
        $formErrors = $form->getErrors(true, true);

       
        if($form->isSubmitted()){
            foreach ($formErrors as $error) {
                $errors[] = $error->getMessage();
            }
        }

       if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->persist($user);
            $entityManager->flush();
           return $this->redirectToRoute('profile');
       }

       return $this->render('profile/edit_email.html.twig', [
           'form' => $form->createView(),
           'errors' => $errors
       ]);
    }

    #[Route('/profile/edit-password', name: 'profile_edit_password')]
    
    public function editPassword(Request $request, UserPasswordHasherInterface $userPasswordHasher,EntityManagerInterface $entityManager): Response
    {
       $user = $this->getUser();
       $form = $this->createForm(EditPasswordType::class);

       $form->handleRequest($request);
       $errors = [];
        $formErrors = $form->getErrors(true, true);

       
        if($form->isSubmitted()){
            foreach ($formErrors as $error) {
                $errors[] = $error->getMessage();
            }
            if($form->get('newPassword')->getData() !== $form->get('confirmPassword')->getData()){
                $errors[] = ' les mots de passe ne correspondent pas';
            }
        }

       if ($form->isSubmitted() && $form->isValid() && $form->get('newPassword')->getData() === $form->get('confirmPassword')->getData()){
           $data = $form->getData();
           $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('newPassword')->getData()
                )
            );
            $user->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('profile');
       }

       return $this->render('profile/edit_password.html.twig', [
           'form' => $form->createView(),
           'errors' => $errors
       ]);
    }
}
