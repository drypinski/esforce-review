<?php

namespace App\Services\ApiClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class TypicodeApiClient implements ApiClientInterface
{
    private const string BASE_URL = 'https://jsonplaceholder.typicode.com';

    public function __construct(private HttpClientInterface $httpClient)
    {}

    public function getPosts(array $filters = []): array
    {
        $query = [];
        $filters = $this->getFilters($filters, [self::FILTER_AUTHOR, self::FILTER_POST]);

        if (!empty($author = $filters[self::FILTER_AUTHOR]) && is_numeric($author)) {
            $query['userId'] = $author;
        }

        if (!empty($ids = $filters[self::FILTER_POST]) && is_array($ids)) {
            $ids = array_values(array_filter($ids, fn(mixed $id) => is_numeric($id)));

            if (!empty($ids)) {
                $query['id'] = $ids;
            }
        }

        $posts = $this->httpClient
            ->request('GET', $this->path('/posts'), [
                'query' => $query,
            ])
            ->toArray();

        if (empty($posts)) {
            return [];
        }

        $authors = $this->getAuthors([self::FILTER_AUTHOR => array_unique(array_column($posts, 'userId'))]);

        foreach ($posts as &$post) {
            $author = $this->findAuthorById($post['userId'] ?? null, $authors);
            $post['author'] = [
                'id' => $author['id'] ?? null,
                'name' => $author['name'] ?? null,
            ];

            unset($post['userId']);
        }

        return $posts;
    }

    public function getPost(string $id): array
    {
        if (!is_numeric($id)) {
            return [];
        }

        $post = $this->httpClient
            ->request('GET', $this->path('/posts/'.$id))
            ->toArray();

        $authors = $this->getAuthors([self::FILTER_AUTHOR => [$authorId = $post['userId'] ?? null]]);
        $author = $this->findAuthorById($authorId, $authors);

        $post['author'] = [
            'id' => $author['id'] ?? null,
            'name' => $author['name'] ?? null,
        ];

        unset($post['userId']);

        return $post;
    }

    public function getAuthors(array $filters = []): array
    {
        $query = [];
        $filters = $this->getFilters($filters, [self::FILTER_AUTHOR]);

        if (!empty($authors = $filters[self::FILTER_AUTHOR])) {
            if (is_array($authors)) {
                $query['id'] = array_values(array_filter($authors, fn(mixed $id) => is_numeric($id)));
            } elseif (is_numeric($authors)) {
                $query['id'] = [$authors];
            }
        }

        $authors = $this->httpClient
            ->request('GET', $this->path('/users'), [
                'query' => $query,
            ])
            ->toArray();

        return array_map(fn (array $author) => array_intersect_key($author, ['id' => null, 'name' => null]), $authors);
    }

    private function path(string $path): string
    {
        return sprintf('%s%s', self::BASE_URL, $path);
    }

    private function getFilters(array $filters, array $keys): array
    {
        $filtered = array_filter(
            $filters,
            fn(mixed $value, mixed $key) => !empty($value) && in_array($key, $keys, true),
            ARRAY_FILTER_USE_BOTH
        );

        return array_replace(array_fill_keys($keys, null), $filtered);
    }

    private function findAuthorById(string|null $authorId, array $authors): array
    {
        if (empty($authorId)) {
            return [];
        }

        $found = array_values(
            array_filter($authors, fn(array $author) => isset($author['id']) && (string)$author['id'] === $authorId)
        );

        return empty($found) ? [] : current($found);
    }
}
