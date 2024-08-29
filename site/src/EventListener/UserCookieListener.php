<?php

namespace App\EventListener;

use App\Manger\UserManager;
use App\Services\UserProvider;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Uid\Uuid;

final readonly class UserCookieListener
{
    public function __construct(private UserProvider $userProvider, private UserManager $userManager)
    {}

    #[AsEventListener(event: KernelEvents::RESPONSE)]
    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        if (null === $user = $this->userProvider->getUser()) {
            $user = $this->userManager->getOrCreate(Uuid::v4());
            $this->userProvider->login($user);
        }

        $response = $event->getResponse();
        $response->headers->setCookie(new Cookie(
            $this->userProvider::COOKIE_KEY,
            (string) $user->getId(),
            time() + 60*60*24*30
        ));
    }
}
