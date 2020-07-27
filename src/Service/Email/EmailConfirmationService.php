<?php
namespace App\Service\Email;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class EmailConfirmationService implements EmailConfirmationServiceInterface
{
    private $resetPasswordHelper;

    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
    }

    public function emailContent($user): TemplatedEmail
    {
        $content = (new TemplatedEmail())
        ->from(new Address('somemail@mail.com', 'Mail Bot'))
        ->to($user->getEmail())
        ->subject('Please Confirm your Email')
        ->htmlTemplate('registration/confirmation_email.html.twig');

        return $content;
    }

    public function resetPasswordContent($user, $resetToken): TemplatedEmail
    {
        $content = (new TemplatedEmail())
        ->from(new Address('somemail@mail.com', 'Mail bot'))
        ->to($user->getEmail())
        ->subject('Your password reset request')
        ->htmlTemplate('reset_password/email.html.twig')
        ->context([
            'resetToken' => $resetToken,
            'tokenLifetime' => $this->resetPasswordHelper->getTokenLifetime(),
        ]);

        return $content;
    }
}