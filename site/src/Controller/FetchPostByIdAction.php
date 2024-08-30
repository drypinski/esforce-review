<?php

namespace App\Controller;

use App\Query\FetchPostById\Fetcher;
use App\Query\FetchPostById\Query;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/posts/{id<\d+>}', name: 'app_post_by_id')]
final class FetchPostByIdAction
{
    public function __invoke(string $id, Fetcher $fetcher): JsonResponse
    {
        $query = new Query();
        $query->id = $id;

        return new JsonResponse($fetcher->fetch($query));
    }
}
