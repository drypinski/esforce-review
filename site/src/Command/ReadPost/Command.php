<?php

namespace App\Command\ReadPost;

use App\Validator as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    #[Assert\NotBlank]
    #[Assert\Type(type: 'numeric')]
    #[Assert\Positive]
    public string|null $postId = null;

    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[CustomAssert\ExistsUser]
    public string|null $userId = null;
}
