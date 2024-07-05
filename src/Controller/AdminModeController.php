<?php

namespace App\Controller;

use App\Form\EditRoleType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/adminMode')]
class AdminModeController extends AbstractController
{
    #[Route('/UserTable', name: 'app_admin_mode_user')]
    public function userTable(Request $request, UserRepository $userRepository): Response
    {   
        $firstname = $request->query->get('firstname');
        $name = $request->query->get('name');
        $email = $request->query->get('email');
        $role = $request->query->get('role');
        if( $firstname || $email || $name || $role ){
            $users = $userRepository->searchUsers($firstname, $name, $email, $role);
        }else{
            $users = $userRepository->getAllUsers();
        }

        return $this->render('admin_mode/user/user.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/UserTable/{id}', name: 'app_admin_mode_user_edit')]
    public function userEdit(UserRepository $userRepository, $id, Request $request,EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->getUserById($id);
        $form = $this->createForm(EditRoleType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new \DateTimeImmutable());
            $user->setRoles([$form->get('role')->getData()]);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin_mode_user');
        }
        $form->get('role')->setData($user->getRoles()[0]);

        return $this->render('admin_mode/user/user_edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
