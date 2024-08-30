<?php

namespace App\Controller;

use App\Query\FetchAuthors\Fetcher;
use App\Query\FetchAuthors\Query;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/authors', name: 'app_authors')]
final class FetchAuthorsAction
{
    public function __invoke(Fetcher $fetcher): JsonResponse
    {
        return new JsonResponse($fetcher->fetch(new Query()));
    }
}
