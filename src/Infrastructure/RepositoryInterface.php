<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\EntityInterface;

interface RepositoryInterface
{
    public function insert(EntityInterface $entity): void;

    public function update(EntityInterface $entity): void;

    public function refresh(EntityInterface $entity): void;

    public function remove(EntityInterface $entity): void;
}
