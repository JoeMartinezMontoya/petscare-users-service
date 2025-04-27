<?php
namespace App\Tests\Application\Exception;

use App\Application\Exception\InvalidDataFormatException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('exception')]
final class InvalidDataFormatExceptionTest extends TestCase
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
                'https://api.petscare.com/error/invalid-data-format',
                (new InvalidDataFormatException())->getType(),
            ],
            'title'       => [
                'Invalid Data Format',
                (new InvalidDataFormatException())->getTitle(),
            ],
            'message'     => [
                'Data can not be processed.',
                (new InvalidDataFormatException())->getDetail(),
            ],
            'status code' => [
                400,
                (new InvalidDataFormatException())->getStatusCode(),
            ],
        ];
    }

    public function testMustReturnsTheRightCustomMessage(): void
    {
        $exception = new InvalidDataFormatException('Some custom message');
        $this->assertSame('Some custom message', $exception->getDetail());
    }

}
