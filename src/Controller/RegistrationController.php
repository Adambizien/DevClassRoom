<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Bundle\SecurityBundle\Security;

class RegistrationController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        if ($this->security->isGranted('ROLE_USER') || $this->security->isGranted('ROLE_AWAITING_VALIDATION') || $this->security->isGranted('ROLE_TEACHER') || $this->security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_home');
        }
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $errors = [];
        $formErrors = $form->getErrors(true, true);

       
        if($form->isSubmitted()){
            foreach ($formErrors as $error) {
                $errors[] = $error->getMessage();
            }
        }
        

        if ($form->isSubmitted() && $form->isValid()) {
           
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $today = new \DateTimeImmutable();
            $user->setCreatedAt($today)
                ->setUpdatedAt($today)
                ->setStatus('active')
                ->setRoles([$form->get('role')->getData()]);

            

            $entityManager->persist($user);
            $entityManager->flush();

            

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
            'errors' => $errors

        ]);
    }
}
