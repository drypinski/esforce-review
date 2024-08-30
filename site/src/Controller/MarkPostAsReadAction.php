<?php

namespace App\Controller;

use App\Command\CreateUser\Command as CreateUserCommand;
use App\Command\CreateUser\Handler as CreateUserHandler;
use App\Command\ReadPost\Command as ReadPostCommand;
use App\Command\ReadPost\Handler as ReadPostHandler;
use App\Entity\User;
use App\Services\UserProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/posts/{id<\d+>}/read', name: 'app_read_post', methods: ['POST'])]
final class MarkPostAsReadAction
{
    public function __invoke(
        string $id,
        Request $request,
        ReadPostHandler $readPostHandler,
        CreateUserHandler $createUserHandler,
        UserProvider $userProvider
    ): Response {
        if (null === $user = $userProvider->getUser()) {
            $user = $this->createUser($request, $createUserHandler, $userProvider);
        }

        $command = new ReadPostCommand();
        $command->postId = $id;
        $command->userId = (string) $user->getId();

        $readPostHandler->handle($command);

        return new Response(null, Response::HTTP_CREATED);
    }

    private function createUser(Request $request, CreateUserHandler $createUserHandler, UserProvider $userProvider): User
    {
        $command = new CreateUserCommand();
        $command->token = $request->headers->get('X-Token');

        $userId = $createUserHandler->handle($command);

        if (null !== $user = $userProvider->find($userId)) {
            $userProvider->login($user);

            return $user;
        }

        throw new NotFoundHttpException('User not found');
    }
}
