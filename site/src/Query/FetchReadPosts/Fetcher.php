<?php

namespace App\Query\FetchReadPosts;

use App\Entity\PostView;
use App\Query\FetchPosts\Fetcher as PostsFetcher;
use App\Query\FetchPosts\Query as PostsQuery;
use App\Repository\PostViewRepository;
use App\Services\ObjectValidator;

final readonly class Fetcher
{
    public function __construct(
        private ObjectValidator $validator,
        private PostViewRepository $postViewRepository,
        private PostsFetcher $postsFetcher
    ) {}

    public function fetch(Query $query): array
    {
        $this->validator->validate($query);

        $views = $this->postViewRepository->findBy(['user' => $query->userId]);

        if (empty($views)) {
            return [];
        }

        $ids = array_map(static fn(PostView $view) => (int) $view->getPostKey(), $views);

        return array_values(array_filter($this->getPosts(), fn(array $post) => in_array($post['id'], $ids, true)));
    }

    private function getPosts(): array
    {
        return $this->postsFetcher->fetch(new PostsQuery());
    }
}
