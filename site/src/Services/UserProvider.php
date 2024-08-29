<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;

final class UserProvider
{
    public const string COOKIE_KEY = 'identifier';

    private RequestStack $requestStack;
    private User|null $user = null;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function login(User $user): void
    {
        $this->user = $user;

        if (null !== $request = $this->requestStack->getMainRequest()) {
            $request->cookies->set(self::COOKIE_KEY, (string) $user->getId());
        }
    }
}
