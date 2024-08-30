<?php

namespace App\Query\FetchPostById;

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

        $key = 'post.id_'.$query->id;

        return $this->cache->get($key, function (ItemInterface $item) use ($query): array {
            $item->expiresAfter($this->cacheTime);

            return $this->api->getPost($query->id);
        });
    }
}
