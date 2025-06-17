<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactTypeForm;
use App\Service\FormValid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/contact', name: 'app_contact')]
final class ContactController extends AbstractController
{
    #[Route('/')]
    public function index(Request $request, MailerInterface $mailer, FormValid $form_valid): Response
    {
        $contact = new Contact();
        $form_contact = $this->createForm(ContactTypeForm::class);

        $form_contact->handleRequest($request);

        if($form_contact->isSubmitted() && $form_contact->isValid()){
            $form_valid->validAndSubmittedForm($mailer);
            // $email = (new Email())
            //     ->from('hello@example.com')
            //     ->to('you@example.com')
            //     ->subject('Time for Symfony Mailer!')
            //     ->text('Sending emails is fun again!')
            //     ->html('<p>See Twig integration for better HTML integration!</p>');

            // $mailer->send($email);
            $this->addFlash('success', "Votre mail a bien été envoyé");
            return $this->redirect('admin.recipe.home');
        }

        return $this->render('contact/index.html.twig', [
            'form_contact' => $form_contact,
        ]);
    }
}
