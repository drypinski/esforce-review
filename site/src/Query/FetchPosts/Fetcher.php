<?php

namespace App\Query\FetchPosts;

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
        private int $cacheTime
    ) {}

    public function fetch(Query $query): array
    {
        $this->validator->validate($query);

        if (!$query->useCache) {
            return $this->fetchPosts($query);
        }

        $key = 'posts';

        if (null !== $query->authorId) {
            $key = sprintf('%s.author_%s', $key, $query->authorId);
        }

        return $this->cache->get($key, function (ItemInterface $item) use ($query): array {
            $item->expiresAfter($this->cacheTime);

            return $this->fetchPosts($query);
        });
    }

    private function fetchPosts(Query $query): array
    {
        return $this->api->getPosts([$this->api::FILTER_AUTHOR => $query->authorId]);
    }
}
