<?php

namespace App\Controller;

use App\Manger\PostManager;
use App\Services\UserProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    #[Route('/posts', name: 'app_posts')]
    public function index(Request $request, PostManager $manager): JsonResponse
    {
        return $this->json($manager->getPosts($request->query->all()));
    }

    #[Route('/posts/{id<\d+>}', name: 'app_post')]
    public function show(string $id, PostManager $manager): JsonResponse
    {
        return $this->json($manager->getPost($id));
    }

    #[Route('/posts/{id<\d+>}/viewed', name: 'app_posts_viewed', methods: ['POST'])]
    public function viewed(PostManager $postManager, UserProvider $userProvider, string $id): JsonResponse
    {
        if (null === $user = $userProvider->getUser()) {
            return $this->json(null, Response::HTTP_NOT_FOUND);
        }

        $postManager->viewPost($user, $id);

        return $this->json(null, Response::HTTP_CREATED);
    }
}
