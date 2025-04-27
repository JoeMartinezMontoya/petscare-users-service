<?php
namespace App\Tests\Application\Exception;

use App\Domain\Exception\TimeParadoxException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('exception')]
final class TimeParadoxExceptionTest extends TestCase
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
                'https://api.petscare.com/error/time-paradox',
                (new TimeParadoxException())->getType(),
            ],
            'title'       => [
                'Time Paradox',
                (new TimeParadoxException())->getTitle(),
            ],
            'message'     => [
                'Time paradox detected.',
                (new TimeParadoxException())->getDetail(),
            ],
            'status code' => [
                400,
                (new TimeParadoxException())->getStatusCode(),
            ],
        ];
    }

    public function testMustReturnsTheRightCustomMessage(): void
    {
        $exception = new TimeParadoxException('Some custom message');
        $this->assertSame('Some custom message', $exception->getDetail());
    }

}
