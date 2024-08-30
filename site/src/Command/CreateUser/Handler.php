<?php

namespace App\Command\CreateUser;

use App\Entity\User;
use App\Exception\ValidationException;
use App\Repository\UserRepository;
use App\Services\ObjectValidator;
use Symfony\Component\Uid\Uuid;

final readonly class Handler
{
    public function __construct(private ObjectValidator $validator, private UserRepository $userRepository)
    {}

    /**
     * @throws ValidationException
     */
    public function handle(Command $command): string
    {
        $this->validator->validate($command);

        $user = new User();

        if (null !== $command->uuid) {
            $user->setUuid(Uuid::fromString($command->uuid));
        }

        $this->userRepository->persist($user);

        return (string) $user->getId();
    }
}
