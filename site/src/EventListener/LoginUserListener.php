<?php

namespace App\EventListener;

use App\Repository\UserRepository;
use App\Services\UserProvider;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final readonly class LoginUserListener
{
    public function __construct(private UserProvider $userProvider, private UserRepository $userRepository)
    {}

    #[AsEventListener(event: KernelEvents::REQUEST)]
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        if ('' === $uuid = $event->getRequest()->cookies->getString($this->userProvider::COOKIE_KEY)) {
            return;
        }

        if (null === $user = $this->userRepository->find($uuid)) {
            return;
        }

        $this->userProvider->login($user);
    }
}
