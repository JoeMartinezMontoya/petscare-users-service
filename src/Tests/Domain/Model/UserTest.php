<?php
namespace App\Tests\Domain\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('model')]
final class UserTest extends TestCase
{
    #[DataProvider('rulesViolationProvider')]
    public function testMustRespectDomainLogic(callable $factory, string $exception, string $message): void
    {
        $this->expectException($exception);
        $this->expectExceptionMessage($message);
        $factory();
    }

    public static function rulesViolationProvider()
    {
        // return [
        //     'can not start in the past'                     => [
        //         fn(): Announcement => self::testAnnouncement(startingDate: '2024-02-01'),
        //         TimeParadoxException::class,
        //         'Starting date can not be prior the creation date.',
        //     ],
        //     'can not start in over a year'                  => [
        //         fn(): Announcement => self::testAnnouncement(startingDate: '2030-02-02'),
        //         DomainRuleViolationException::class,
        //         'Starting date can not be over a year from now.',
        //     ],
        //     'can not expire in the past'                    => [
        //         fn(): Announcement => self::testAnnouncement(expirationDate: '2024-02-01'),
        //         TimeParadoxException::class,
        //         'Expiration date can not be prior the creation date.',
        //     ],
        //     'can not expire in over a year'                 => [
        //         fn(): Announcement => self::testAnnouncement(expirationDate: '2030-02-02'),
        //         DomainRuleViolationException::class,
        //         'Expiration date can not be over a year from now.',
        //     ],
        //     'can not be updated in the past'                => [
        //         fn(): Announcement => self::testAnnouncement(updatedAt: '2024-02-01'),
        //         TimeParadoxException::class,
        //         'Modification date can not be prior the creation date.',
        //     ],
        //     'must not have a title if is lost announcement' => [
        //         fn(): Announcement => self::testAnnouncement(title: 'A whole bunch'),
        //         DomainRuleViolationException::class,
        //         'Lost type announcement can not have a title.',
        //     ],
        // ];
    }

    private static function testUser(?string $title = null, ?string $startingDate = null, ?string $expirationDate = null, ?string $updatedAt = null)
    {
        // null !== $title ? $title                   = new Title($title) : null;
        // null !== $startingDate ? $startingDate     = new StartingDate(new \DateTimeImmutable($startingDate)) : null;
        // null !== $expirationDate ? $expirationDate = new ExpirationDate(new \DateTimeImmutable($expirationDate)) : null;
        // null !== $updatedAt ? $updatedAt           = new UpdatedAt(new \DateTimeImmutable($updatedAt)) : null;

        // return new Announcement(
        //     new Id((new UuidGenerator)->generate()),
        //     $title,
        //     new Content('
        //     Lorem, ipsum dolor sit amet consectetur adipisicing elit. Illum, optio hic expedita sint est aliquid eum quidem, quis eaque omnis dicta similique corporis praesentium fuga placeat rerum possimus et. Cum.'),
        //     new UserId((new UuidGenerator)->generate()),
        //     $startingDate,
        //     $expirationDate,
        //     new CreatedAt(new \DateTimeImmutable()),
        //     $updatedAt,
        //     new Location('Paris'),
        //     new City('Paris'),
        //     new Postcode('75000'),
        //     new Latitude(0.85),
        //     new Longitude(0.85),
        //     new PetId((new UuidGenerator)->generate()),
        //     new ViewsCount(0),
        //     new Type(1),
        //     new Status(1),
        // );
    }
}
