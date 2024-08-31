<?php

namespace App\Query\FetchPosts;

use App\Repository\PostViewRepository;
use App\Services\ApiClient\ApiClientInterface;
use App\Services\ObjectValidator;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final readonly class Fetcher
{
    public function __construct(
        private ObjectValidator $validator,
        private ApiClientInterface $api,
        private CacheInterface $cache,
        private PostViewRepository $postViewRepository,
        private int $cacheTime
    ) {}

    public function fetch(Query $query): array
    {
        $this->validator->validate($query);

        if (!$query->useCache) {
            return $this->updateReadField($query, $this->fetchPosts($query));
        }

        $key = 'posts';

        if (null !== $query->authorId) {
            $key = sprintf('%s.author_%s', $key, $query->authorId);
        }

        $posts = $this->cache->get($key, function (ItemInterface $item) use ($query): array {
            $item->expiresAfter($this->cacheTime);

            return $this->fetchPosts($query);
        });

        return $this->updateReadField($query, $posts);
    }

    private function fetchPosts(Query $query): array
    {
        $posts = $this->api->getPosts([$this->api::FILTER_AUTHOR => $query->authorId]);

        foreach ($posts as &$post) {
            $post['read'] = false;
        }

        return $posts;
    }

    private function updateReadField(Query $query, array $posts): array
    {
        if (null === $query->userId) {
            return $posts;
        }

        $ids = $this->postViewRepository->findReadPostsIds($query->userId);

        if (empty($ids)) {
            return $posts;
        }

        foreach ($posts as &$post) {
            $post['read'] = in_array($post['id'] ?? null, $ids, true);
        }

        return $posts;
    }
}
