<?php

namespace App\Controller;

use App\Helper\HttpCacheHelper;
use App\Query\FetchAuthors\Fetcher;
use App\Query\FetchAuthors\Query;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/authors', name: 'app_authors')]
final class FetchAuthorsAction
{
    public function __invoke(Fetcher $fetcher): Response
    {
        return HttpCacheHelper::withCache(new JsonResponse($fetcher->fetch(new Query())), HttpCacheHelper::TIME_5MIN);
    }
}
