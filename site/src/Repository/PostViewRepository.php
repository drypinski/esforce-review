<?php

namespace App\Repository;

use App\Entity\PostView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostView>
 */
class PostViewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostView::class);
    }

    public function persist(PostView $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        $flush && $this->flush();
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    /**
     * @return array<int>
     */
    public function findReadPostsIds(string $userId): array
    {
        $views = $this->findBy(['user' => $userId]);

        if (empty($views)) {
            return [];
        }

        return array_map(static fn(PostView $view) => (int) $view->getPostKey(), $views);
    }
}
