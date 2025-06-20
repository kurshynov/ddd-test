<?php

declare(strict_types=1);

namespace App\UI\Http\Controller\Api\V1;

use App\Application\Loan\Command\LoanEligibilityAddCommand;
use App\Application\Loan\Event\LoanEligibilityCheckedEvent;
use App\Application\Loan\Factory\LoanApplicationFactory;
use App\Application\Loan\Handler\LoanEligibilityAddHandler;
use App\Domain\Loan\Service\LoanEligibilityCheckerService;
use App\UI\Http\Controller\Api\BaseController;
use App\UI\Http\Dto\Request\Api\V1\Loan\LoanEligibilityAddRequest;
use App\UI\Http\Dto\Request\Api\V1\Loan\LoanEligibilityCheckerRequest;
use App\UI\Http\Dto\Response\Api\V1\Loan\LoanEligibilityAddResponse;
use App\UI\Http\Dto\Response\Api\V1\Loan\LoanEligibilityCheckerResponse;
use App\UI\Http\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Throwable;

#[Route('/loan', name: 'api_')]
class LoanController extends BaseController
{
    /**
     * @throws ExceptionInterface
     * @throws Throwable
     */
    #[Route('/add', name: 'loan_add', methods: ['PUT'])]
    public function add(
        #[MapRequestPayload] LoanEligibilityAddRequest $request,
        LoanEligibilityAddResponse $response,
        LoanEligibilityAddHandler $handler
    ): Response {
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
    }

    /**
     * @throws ApiException
     * @throws ExceptionInterface
     * @throws Throwable
     */
    #[Route('/check', name: 'loan_check', methods: ['POST'])]
    public function checkEligibility(
        #[MapRequestPayload] LoanEligibilityCheckerRequest $request,
        LoanEligibilityCheckerResponse $response,
        LoanApplicationFactory $loanApplicationFactory,
        LoanEligibilityCheckerService $loanEligibilityCheckerService,
        EventDispatcherInterface $eventDispatcher
    ): Response {
        $loanApplication = $loanApplicationFactory->create(
            $request->getUserId(),
            $request->getLoanId()
        );

        $result = $loanEligibilityCheckerService->check(
            application: $loanApplication
        );

        $eventDispatcher->dispatch(
            new LoanEligibilityCheckedEvent(
                userId: $request->getUserId(),
                isEligible: $result->isEligible(),
                rate: $result->getAdjustedRate()
            )
        );

        return $this->getResponse(
            $response
                ->setSuccess(true)
                ->setIsEligible($result->isEligible())
                ->setReasons($result->getReasons())
                ->setAdjustedRate($result->getAdjustedRate())
        );
    }
}
