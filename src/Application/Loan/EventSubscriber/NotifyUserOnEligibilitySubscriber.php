<?php

declare(strict_types=1);

namespace App\Application\Loan\EventSubscriber;

use App\Application\Loan\Event\LoanEligibilityCheckedEvent;
use App\Infrastructure\User\Doctrine\UserRepository;
use App\Tools\ToolsHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Throwable;

class NotifyUserOnEligibilitySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private UserRepository $userRepository
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [LoanEligibilityCheckedEvent::class => 'onCheck'];
    }

    public function onCheck(LoanEligibilityCheckedEvent $event): void
    {
        try {
            $user = $this->userRepository->findById($event->getUserId());

            $dateTime = ToolsHelper::getTimestamp()->format('Y-m-d H:i');
            $message = $event->isEligible()
                ? "Поздравляем! Ваш кредит одобрен. Ставка: {$event->getRate()}%."
                : "К сожалению, ваш кредит не может быть одобрен.";
            $loanStatus = $event->isEligible() ? 'Кредит одобрен' : 'Кредит отклонён';

            // [Дата/время] Уведомление клиенту [Имя клиента]: Кредит одобрен/отклонен.).
            $this->logger->info(
                $dateTime.' '.$message.' ['.$user->getName().']: '.$loanStatus
            );
        } catch (Throwable $e) {
            $this->logger->error('NotifyUserOnEligibilitySubscriber::onCheck', [$e]);
        }
    }
}
