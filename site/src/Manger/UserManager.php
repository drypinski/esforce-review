<?php

namespace App\Manger;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Uid\Uuid;

final readonly class UserManager
{
    public function __construct(private UserRepository $repository)
    {}

    public function find(Uuid|string $uuid): ?User
    {
        return $this->repository->find((string) $uuid);
    }

    public function getOrCreate(Uuid|string $uuid): User
    {
        if (null === $user = $this->find($uuid)) {
            $user = new User();
            $user->setUuid(Uuid::fromString((string) $uuid));
            $this->repository->persist($user);
        }

        return $user;
    }
}
