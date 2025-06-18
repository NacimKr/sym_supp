<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ResponseSubscriber implements EventSubscriberInterface
{

    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
    }

    public function sendMail(ControllerEvent $event): void
    {
        $email = (new Email())
          ->from('hello@example.com')
          ->to('you@example.com')
          ->subject('Time for Symfony Mailer!')
          ->text('Sending emails is fun again!')
          ->html('<p>See Twig integration for better HTML integration!</p>');

      $this->mailer->send($email);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => 'sendMail',
        ];
    }
}
