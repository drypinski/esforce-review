<?php

namespace App\Controller;

use App\Services\ApiClient\ApiClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class AuthorController extends AbstractController
{
    #[Route('/authors', name: 'app_authors')]
    public function index(ApiClientInterface $api): JsonResponse
    {
        return $this->json($api->getAuthors());
    }
}
