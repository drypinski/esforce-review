<?php

namespace App\Controller;

use App\Query\FetchReadPosts\Fetcher;
use App\Query\FetchReadPosts\Query;
use App\Services\UserProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/read-posts', name: 'app_user_read_posts')]
final class FetchReadPostsAction
{
    public function __invoke(UserProvider $userProvider, Fetcher $fetcher): JsonResponse
    {
        if (null === $user = $userProvider->getUser()) {
            return new JsonResponse([]);
        }

        $query = new Query();
        $query->userId = (string) $user->getId();

        return new JsonResponse($fetcher->fetch($query));
    }
}
