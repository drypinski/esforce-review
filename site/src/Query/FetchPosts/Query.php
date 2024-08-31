<?php

namespace App\Query\FetchPosts;

use Symfony\Component\Validator\Constraints as Assert;

final class Query
{
    public bool $useCache = true;

    #[Assert\Type(type: 'numeric')]
    public string|null $authorId = null;

    #[Assert\Uuid]
    public string|null $userId = null;
}
