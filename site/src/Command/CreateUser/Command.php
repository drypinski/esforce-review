<?php

namespace App\Command\CreateUser;

use App\Validator as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    #[Assert\NotBlank(allowNull: true)]
    #[Assert\Uuid]
    public string|null $uuid = null;

    #[Assert\NotBlank]
    #[CustomAssert\ValidCaptcha]
    public string|null $token = null;
}
