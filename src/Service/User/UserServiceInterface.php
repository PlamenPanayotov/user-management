<?php

namespace App\Service\User;

use App\Entity\User;

interface UserServiceInterface
{
    public function currentUser(): ?User;

    public function isVerified(): bool;
}
