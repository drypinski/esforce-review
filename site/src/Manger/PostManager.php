<?php

namespace App\Manger;

use App\Entity\PostView;
use App\Entity\User;
use App\Repository\PostViewRepository;
use App\Services\ApiClient\ApiClientInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final readonly class PostManager
{
    public function __construct(
        private CacheInterface $cache,
        private ApiClientInterface $api,
        private PostViewRepository $postViewRepository,
        private int $postsCacheTime,
        private int $postCacheTime
    ) {}

    public function getViewedPosts(User $user): array
    {
        $views = $this->postViewRepository->findBy(['user' => $user->getId()]);

        if (empty($views)) {
            return [];
        }

        $ids = array_map(static fn(PostView $view) => $view->getPostKey(), $views);

        return array_values(array_filter($this->getPosts(), fn(array $post) => in_array($post['id'], $ids, true)));
    }

    public function getPosts(array $filters = []): array
    {
        $key = 'manager.posts';
        $filters = array_replace(array_fill_keys([$this->api::FILTER_AUTHOR], null), $filters);

        if (!empty($author = $filters[$this->api::FILTER_AUTHOR]) && is_numeric($author)) {
            $key = sprintf('%s.author_%s', $key, $author);
        }

        return $this->cache->get($key, function (ItemInterface $item) use (&$filters): array {
            $item->expiresAfter($this->postsCacheTime);

            return $this->api->getPosts($filters);
        });
    }

    public function getPost(string $id): array
    {
        if (!is_numeric($id)) {
            return [];
        }

        $key = 'manager.post.'.$id;

        return $this->cache->get($key, function (ItemInterface $item) use (&$id): array {
            $item->expiresAfter($this->postCacheTime);

            return $this->api->getPost($id);
        });
    }

    public function viewPost(User $user, string $postId): void
    {
        if (null !== $this->postViewRepository->findOneBy(['user' => $user->getId(), 'postKey' => $postId])) {
            return;
        }

        $view = new PostView();
        $view
            ->setUser($user)
            ->setPostKey($postId);

        $this->postViewRepository->persist($view);
    }
}
