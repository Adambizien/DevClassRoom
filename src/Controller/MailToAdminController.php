<?php

namespace App\Controller;

use App\Form\MailToAdminType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MailToAdminController extends AbstractController
{
    #[Route('/NousContacter', name: 'app_mail_to_admin')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(MailToAdminType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Créez l'email
            $email = (new Email())
            ->from($data['email'])
            ->to('abizien@normandiewebschool.fr')
            ->subject($data['subject'])
            ->text($data['message']);

            $mailer->send($email);

            $this->addFlash('success', 'Your message has been sent.');

          
        }
        return $this->render('mail_to_admin/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
