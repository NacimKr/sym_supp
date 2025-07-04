<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class FormValid{
  public function validAndSubmittedForm(
      MailerInterface $mailer
    ){
      $email = (new Email())
          ->from('hello@example.com')
          ->to('you@example.com')
          ->subject('Time for Symfony Mailer!')
          ->text('Sending emails is fun again!')
          ->html('<p>See Twig integration for better HTML integration!</p>');

      $mailer->send($email);
  }

}