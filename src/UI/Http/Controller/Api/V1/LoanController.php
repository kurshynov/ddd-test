<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Api\V1;

use App\Application\Loan\Command\LoanEligibilityAddCommand;
use App\Application\Loan\Event\LoanEligibilityCheckedEvent;
use App\Application\Loan\Handler\LoanEligibilityAddHandler;
use App\Domain\Loan\Service\LoanEligibilityCheckerService;
use App\Infrastructure\Loan\Doctrine\LoanRepository;
use App\Infrastructure\User\Doctrine\UserRepository;
use App\UI\Http\Controller\Api\BaseController;
use App\UI\Http\Dto\Request\Api\V1\Loan\LoanEligibilityAddRequest;
use App\UI\Http\Dto\Request\Api\V1\Loan\LoanEligibilityCheckerRequest;
use App\UI\Http\Dto\Response\Api\V1\Loan\LoanEligibilityAddResponse;
use App\UI\Http\Dto\Response\Api\V1\Loan\LoanEligibilityCheckerResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Throwable;

#[Route('/loan', name: 'api_')]
class LoanController extends BaseController
{
    #[Route('/add', name: 'loan_add', methods: ['PUT'])]
    public function add(
        #[MapRequestPayload] LoanEligibilityAddRequest $request,
        LoanEligibilityAddResponse $response,
        LoanEligibilityAddHandler $handler
    ): Response {
        try {
            $command = new LoanEligibilityAddCommand(
                name: $request->getName(),
                amount: $request->getAmount(),
                rate: $request->getRate(),
                startDate: $request->getStartDate(),
                endDate: $request->getEndDate()
            );

            $handler->handle($command);

            return $this->getResponse(
                $response->setSuccess(true)
            );
        } catch (Throwable $e) {
            return $this->getErrorResponse($e);
        }
    }

    #[Route('/check', name: 'loan_check', methods: ['POST'])]
    public function checkEligibility(
        #[MapRequestPayload] LoanEligibilityCheckerRequest $request,
        LoanEligibilityCheckerResponse $response,
        LoanEligibilityCheckerService $loanEligibilityCheckerService,
        UserRepository $userRepository,
        LoanRepository $loanRepository,
        EventDispatcherInterface $eventDispatcher
    ): Response {
        try {
            // В правильном варианте мы должны получить пользователя по сессии или токену,
//            $user = $this->getUser();

            $user = $userRepository->findById(
                userId: $request->getUserId()
            );

            $loan = $loanRepository->findById(
                loanId: $request->getLoanId()
            );

            $result = $loanEligibilityCheckerService->check(
                user: $user
            );

            $rate = $loan->getRate() + $result->getAdjustedRate();

            $eventDispatcher->dispatch(
                new LoanEligibilityCheckedEvent(
                    userId: $user->getId(),
                    isEligible: $result->isEligible(),
                    rate: $rate
                )
            );

            return $this->getResponse(
                $response
                    ->setSuccess(true)
                    ->setIsEligible($result->isEligible())
                    ->setReasons($result->getReasons())
                    ->setAdjustedRate($rate)
            );
        } catch (Throwable $e) {
            return $this->getErrorResponse($e);
        }
    }
}
