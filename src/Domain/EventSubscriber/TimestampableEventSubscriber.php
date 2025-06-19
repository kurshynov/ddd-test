<?php

declare(strict_types=1);

namespace App\Domain\EventSubscriber;

use App\Domain\Traits\CreatedAtTimestampableInterface;
use App\Domain\Traits\UpdatedAtTimestampableInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\MappingException;

#[AsDoctrineListener(event: Events::loadClassMetadata)]
final class TimestampableEventSubscriber
{
    /**
     * @param LoadClassMetadataEventArgs $loadClassMetadataEventArgs
     * @return void
     * @throws MappingException
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $loadClassMetadataEventArgs): void
    {
        $classMetadata = $loadClassMetadataEventArgs->getClassMetadata();
        if ($classMetadata->reflClass === null) {
            // Class has not yet been fully built, ignore this event
            return;
        }

        if ($classMetadata->isMappedSuperclass) {
            return;
        }

        // updatedAt не может быть без createdAt !!!
        $isCreatedAt = is_a($classMetadata->reflClass->getName(), CreatedAtTimestampableInterface::class, true);

        if ($isCreatedAt) {
            $classMetadata->addLifecycleCallback('updateTimestamps', Events::prePersist);
            $classMetadata->addLifecycleCallback('updateTimestamps', Events::preUpdate);

            $fields = ['createdAt'];
            if (is_a($classMetadata->reflClass->getName(), UpdatedAtTimestampableInterface::class, true)) {
                $fields = array_merge($fields, ['updatedAt']);
            }

            foreach ($fields as $field) {
                if (!$classMetadata->hasField($field)) {
                    $classMetadata->mapField([
                        'fieldName' => $field,
                        'type' => 'datetime',
                        'nullable' => true,
                    ]);
                }
            }
        }
    }
}
