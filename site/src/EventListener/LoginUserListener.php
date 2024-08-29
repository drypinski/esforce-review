<?php

namespace App\EventListener;

use App\Manger\UserManager;
use App\Services\UserProvider;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Uid\Uuid;

final readonly class LoginUserListener
{
    public function __construct(private UserProvider $userProvider, private UserManager $userManager)
    {}

    #[AsEventListener(event: KernelEvents::REQUEST)]
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $uuid = $event->getRequest()->cookies->get($this->userProvider::COOKIE_KEY, (string) Uuid::v4());
        $user = $this->userManager->getOrCreate($uuid);

        $this->userProvider->login($user);
    }
}
