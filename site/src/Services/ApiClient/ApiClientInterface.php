<?php

namespace App\Services\ApiClient;

interface ApiClientInterface
{
    /*
     * Type of value: number
     */
    public const string FILTER_AUTHOR = 'author';

    /*
     * Type of value: array<number>
     */
    public const string FILTER_POST = 'post';

    public function getPosts(array $filters = []): array;
    public function getPost(string $id): array;
    public function getAuthors(array $filters = []): array;
}
