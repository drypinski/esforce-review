<?php

namespace App\Query\FetchPostById;

use Symfony\Component\Validator\Constraints as Assert;

final class Query
{
    #[Assert\NotBlank]
    #[Assert\Type(type: 'numeric')]
    #[Assert\Positive]
    public string|null $id = null;
}
