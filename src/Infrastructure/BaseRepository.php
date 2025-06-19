<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\EntityInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Exception\ORMException;

abstract class BaseRepository extends ServiceEntityRepository implements RepositoryInterface
{
    #region [Public]

    /**
     * @param EntityInterface|Collection $entity
     * @return void
     */
    public function insert(EntityInterface|Collection $entity): void
    {
        $this->update($entity);
    }

    /**
     * @param EntityInterface|Collection $entity
     * @return void
     */
    public function update(EntityInterface|Collection $entity): void
    {
        if ($entity instanceof EntityInterface) {
            $this->getEntityManager()->persist($entity);
        } else {
            foreach ($entity as $item) {
                if ($item instanceof EntityInterface) {
                    $this->getEntityManager()->persist($item);
                }
            }
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @param EntityInterface $entity
     * @return void
     * @throws ORMException
     */
    public function refresh(EntityInterface $entity): void
    {
        $this->getEntityManager()->refresh($entity);
    }

    /**
     * @param EntityInterface|Collection $entity
     * @return void
     */
    public function remove(EntityInterface|Collection $entity): void
    {
        if ($entity instanceof EntityInterface) {
            $this->getEntityManager()->remove($entity);
        } else {
            foreach ($entity as $item) {
                if ($item instanceof EntityInterface) {
                    $this->getEntityManager()->remove($item);
                }
            }
        }

        $this->getEntityManager()->flush();
    }

    #endregion
}
