<?php

namespace App\Controller;

use App\Exception\UserNotFoundException;
use App\Exception\ValidationException;
use App\Helper\HttpCacheHelper;
use App\Query\FetchPosts\Fetcher;
use App\Query\FetchPosts\Query;
use App\Query\FetchReadPosts\Fetcher as ReadPostsFetcher;
use App\Query\FetchReadPosts\Query as ReadPostsQuery;
use App\Services\UserProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
#[Route('/posts', name: 'app_posts')]
final readonly class FetchPostsAction
{
    public function __construct(
        private ValidatorInterface $validator,
        private ReadPostsFetcher $readPostsFetcher,
        private UserProvider $userProvider
    ) {}

    public function __invoke(Request $request, Fetcher $fetcher): Response
    {
        if ('' !== $userId = $request->query->getString('user')) {
            return $this->fetchReadPosts($userId);
        }

        $query = new Query();
        $query->authorId = $request->query->get('author');

        if (null !== $user = $this->userProvider->getUser()) {
            $query->userId = (string) $user->getId();
        }

        return HttpCacheHelper::withCache(new JsonResponse($fetcher->fetch($query)), HttpCacheHelper::TIME_2MIN);
    }

    /**
     * @throws UserNotFoundException
     */
    private function fetchReadPosts(string $userId): Response
    {
        $errors = $this->validator->validate($userId, [new Uuid()]);

        if ($errors->count() > 0) {
            throw new ValidationException($errors);
        }

        if (null === $this->userProvider->find($userId)) {
            throw new UserNotFoundException('User not Found');
        }

        $query = new ReadPostsQuery();
        $query->userId = $userId;

        return new JsonResponse($this->readPostsFetcher->fetch($query));
    }
}
