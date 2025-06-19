<?php

declare(strict_types=1);

namespace App\Domain\Loan\Service;

use App\Domain\Loan\Entity\LoanEligibilityResult;
use App\Domain\Loan\Rule\RuleInterface;
use App\Domain\User\Entity\User;
use PHPUnit\Framework\TestCase;
use Throwable;

class LoanEligibilityCheckerTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testAllRulesPass(): void
    {
        $rule1 = $this->createMock(RuleInterface::class);
        $rule2 = $this->createMock(RuleInterface::class);

        $rule1->expects($this->once())
            ->method('apply')
            ->willReturnCallback(function (User $user, LoanEligibilityResult $result) {
                // ничего не делаем — правило проходит
            });

        $rule2->expects($this->once())
            ->method('apply')
            ->willReturnCallback(function (User $user, LoanEligibilityResult $result) {
                // ничего не делаем — правило проходит
            });

        $checker = new LoanEligibilityCheckerService([$rule1, $rule2]);

        $user = $this->createMock(User::class);
        $user->method('getScore')->willReturn(600);
        $user->method('getRegion')->willReturn('PR');
        $user->method('getAge')->willReturn(30);
        $user->method('getIncome')->willReturn(1500);

        $result = $checker->check($user);

        $this->assertTrue($result->isEligible());
        $this->assertEmpty($result->getReasons());
    }

    /**
     * @throws Throwable
     */
    public function testRuleFails(): void
    {
        $rule = $this->createMock(RuleInterface::class);

        $rule->expects($this->once())
            ->method('apply')
            ->willReturnCallback(function (User $user, LoanEligibilityResult $result) {
                $result->reject('Too young');
            });

        $checker = new LoanEligibilityCheckerService([$rule]);

        $user = $this->createMock(User::class);
        $user->method('getScore')->willReturn(600);
        $user->method('getRegion')->willReturn('PR');
        $user->method('getAge')->willReturn(12);
        $user->method('getIncome')->willReturn(1500);

        $result = $checker->check($user);

        $this->assertFalse($result->isEligible());
        $this->assertContains('Too young', $result->getReasons());
    }
}
