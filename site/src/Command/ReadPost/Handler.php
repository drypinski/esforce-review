<?php

namespace App\Command\ReadPost;

use App\Entity\PostView;
use App\Repository\PostViewRepository;
use App\Repository\UserRepository;
use App\Services\ObjectValidator;

final readonly class Handler
{
    public function __construct(
        private ObjectValidator $validator,
        private UserRepository $userRepository,
        private PostViewRepository $postViewRepository
    ) {}

    public function handle(Command $command): void
    {
        $this->validator->validate($command);

        if (null !== $this->postViewRepository->findOneBy(['user' => $command->userId, 'postKey' => $command->postId])) {
            return;
        }

        $view = new PostView();
        $view
            ->setUser($this->userRepository->find($command->userId))
            ->setPostKey($command->postId);

        $this->postViewRepository->persist($view);
    }
}
