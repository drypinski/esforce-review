<?php

namespace App\Query\FetchReadPosts;

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

        $ids = $this->postViewRepository->findReadPostsIds($query->userId);

        if (empty($ids)) {
            return [];
        }

        return array_values(array_filter($this->getPosts($query), fn(array $post) => in_array($post['id'], $ids, true)));
    }

    private function getPosts(Query $query): array
    {
        $postQuery = new PostsQuery();
        $postQuery->userId = $query->userId;

        return $this->postsFetcher->fetch($postQuery);
    }
}
