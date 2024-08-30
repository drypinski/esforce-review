<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;

final class UserProvider
{
    public const string COOKIE_KEY = 'identifier';

    private User|null $user = null;
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function login(User $user): void
    {
        $this->user = $user;
    }

    public function find(string $id): ?User
    {
        return $this->userRepository->find($id);
    }
}
