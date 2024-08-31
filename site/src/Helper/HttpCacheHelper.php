<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Response;

final class HttpCacheHelper
{
    public const int TIME_2MIN = 120;
    public const int TIME_5MIN = 300;

    public static function withCache(Response $response, int $time): Response
    {
        $expiresDate = new \DateTime('now', new \DateTimeZone('UTC'));
        $expiresDate->add(new \DateInterval(sprintf('PT%sS', $time)));

        return $response
            ->setMaxAge($time)
            ->setSharedMaxAge($time)
            ->setExpires($expiresDate);
    }
}
