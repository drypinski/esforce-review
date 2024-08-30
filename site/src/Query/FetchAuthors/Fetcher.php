<?php

namespace App\Query\FetchAuthors;

use App\Services\ApiClient\ApiClientInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final readonly class Fetcher
{
    public function __construct(
        private ApiClientInterface $api,
        private CacheInterface $cache,
        private int $cacheTime
    ) {}

    public function fetch(Query $query): array
    {
        if (!$query->useCache) {
            return $this->api->getAuthors();
        }

        $key = 'authors';

        return $this->cache->get($key, function (ItemInterface $item) use ($query): array {
            $item->expiresAfter($this->cacheTime);

            return $this->api->getAuthors();
        });
    }
}
