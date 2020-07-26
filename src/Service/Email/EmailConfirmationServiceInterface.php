<?php
namespace App\Service\Email;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

interface EmailConfirmationServiceInterface
{
    public function emailContent($user): TemplatedEmail;
}