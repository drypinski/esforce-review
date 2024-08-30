<?php

namespace App\EventListener;

use App\Services\UserProvider;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final readonly class UserCookieListener
{
    public function __construct(private UserProvider $userProvider)
    {}

    #[AsEventListener(event: KernelEvents::RESPONSE)]
    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        if (null === $user = $this->userProvider->getUser()) {
            return;
        }

        $event->getResponse()->headers->setCookie(new Cookie(
            $this->userProvider::COOKIE_KEY,
            (string) $user->getId(),
            time() + 60*60*24*30
        ));
    }
}
