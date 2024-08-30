<?php

namespace App\Query\FetchReadPosts;

use App\Validator as CustomAsserts;
use Symfony\Component\Validator\Constraints as Assert;

final class Query
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[CustomAsserts\ExistsUser]
    public string|null $userId = null;
}
