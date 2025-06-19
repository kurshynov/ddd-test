<?php

namespace App\Infrastructure\User\Doctrine;

use App\Domain\User\Entity\User;
use App\Infrastructure\BaseRepository;
use App\UI\Http\Exception\ApiException;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Find a user by their ID.
     *
     * @param int $userId
     * @return User
     * @throws ApiException
     */
    public function findById(int $userId): User
    {
        $user = $this->find($userId);

        if (!$user) {
            // Использую ApiException только для тестового задания
            throw new ApiException(404, 'User not found');
        }

        return $user;
    }
}
