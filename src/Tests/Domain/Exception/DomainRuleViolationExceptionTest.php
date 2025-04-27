<?php
namespace App\Tests\Application\Exception;

use App\Domain\Exception\DomainRuleViolationException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('exception')]
final class DomainRuleViolationExceptionTest extends TestCase
{
    #[DataProvider('exceptionFieldsProvider')]
    public function testMustHaveTheCorrectDefaultValues(mixed $value, mixed $field): void
    {
        $this->assertSame($value, $field);
    }

    public static function exceptionFieldsProvider(): array
    {
        return [
            'type'        => [
                'https://api.petscare.com/error/domain-rule-violation',
                (new DomainRuleViolationException())->getType(),
            ],
            'title'       => [
                'Domain Rule Violation',
                (new DomainRuleViolationException())->getTitle(),
            ],
            'message'     => [
                'Invalid data provided.',
                (new DomainRuleViolationException())->getDetail(),
            ],
            'status code' => [
                400,
                (new DomainRuleViolationException())->getStatusCode(),
            ],
        ];
    }

    public function testMustReturnsTheRightCustomMessage(): void
    {
        $exception = new DomainRuleViolationException('Some custom message');
        $this->assertSame('Some custom message', $exception->getDetail());
    }

}
