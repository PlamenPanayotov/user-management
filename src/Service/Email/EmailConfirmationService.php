<?php
namespace App\Service\Email;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class EmailConfirmationService implements EmailConfirmationServiceInterface
{
    public function emailContent($user): TemplatedEmail
    {
        $content = (new TemplatedEmail())
        ->from(new Address('somemail@mail.com', 'Mail Bot'))
        ->to($user->getEmail())
        ->subject('Please Confirm your Email')
        ->htmlTemplate('registration/confirmation_email.html.twig');

        return $content;
    }
}