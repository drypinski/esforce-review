<?php

namespace App\Services\Recaptcha;

use ReCaptcha\ReCaptcha as GoogleReCaptcha;

final readonly class Recaptcha
{
    public function __construct(private GoogleReCaptcha $reCaptcha)
    {}

    public function isValid(string $token): bool
    {
        $response = $this->reCaptcha->verify($token);

        return $response->isSuccess();
    }
}
