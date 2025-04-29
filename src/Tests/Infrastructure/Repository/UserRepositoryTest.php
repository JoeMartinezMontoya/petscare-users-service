<?php
namespace App\Tests\Infrastructure\Repository;

use App\Domain\Model\UserProfile;
use App\Domain\ValueObjects\BirthDate;
use App\Domain\ValueObjects\CreatedAt;
use App\Domain\ValueObjects\FirstName;
use App\Domain\ValueObjects\Id;
use App\Domain\ValueObjects\LastName;
use App\Domain\ValueObjects\UserName;
use App\Infrastructure\Repository\UserRepository;
use App\Infrastructure\Service\UuidGenerator;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

#[Group('integration')]
#[Group('repository')]
class UserRepositoryTest extends KernelTestCase
{
    private UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = static::getContainer()->get(UserRepository::class);
    }

    public function testMustSaveAnnouncementInDatabase(): void
    {
        $result = $this->repository->save(self::domainUser());
        $this->assertTrue($result);
    }

    // public function testMustReturnAnErrorIfDataIsInvalid(): void
    // {
    //     $this->expectException(TypeError::class);
    //     $this->repository->save(self::domainAnnouncement(false));
    // }

    // public function testMustGetAnnouncementData(): void
    // {
    //     $announcement = $this->repository->save(self::domainAnnouncement());

    //     $result = $this->repository->getAnnouncementData($announcement);
    //     $this->assertNotNull($result);
    //     $this->assertIsObject($result);
    // }

    // public function testMustGetAnnouncementsCount(): void
    // {
    //     $result = $this->repository->getAnnouncementsCount();
    //     $this->assertIsInt($result);
    // }

    // public function testMustGetAllAnnouncements(): void
    // {
    //     $result = $this->repository->getAllAnnouncements();
    //     $this->assertNotNull($result);
    //     $this->assertIsArray($result);
    //     $this->assertCount($this->repository->getAnnouncementsCount(), $result);
    // }

    // public function testMustReturnAnErrorIfResourceNotFound(): void
    // {
    //     $this->expectException(AnnouncementNotFoundException::class);
    //     $this->repository->getAnnouncementData('00d76fd4-03bb-4161-a4eb-1bfb68dc4a8e');
    // }

    // public function testMustReturnUserAnnouncements(): void
    // {
    //     $announcements = $this->repository->getUserAnnouncements('d8cbbfa5-d391-4370-9542-c003b4f9ca43');
    //     $this->assertIsArray($announcements);
    //     $this->assertInstanceOf(Announcement::class, $announcements[0]);
    // }

    // public function testMustIncrementViewsCount(): void
    // {
    //     $announcementId = $this->repository->save(self::domainAnnouncement());

    //     $monitoredAnnouncement = $this->repository->getAnnouncementData($announcementId);
    //     $this->repository->incrementViewsCount($monitoredAnnouncement->getId()->getValue());
    //     $this->assertEquals(0, $monitoredAnnouncement->getViewsCount()->getValue());
    //     $updatedAnnouncement = $this->repository->getAnnouncementData($announcementId);
    //     $this->assertEquals(1, $updatedAnnouncement->getViewsCount()->getValue());
    // }

    private static function domainUser(): UserProfile
    {
        return new UserProfile(
            new Id((new UuidGenerator)->generate()),
            new UserName('Admin'),
            new FirstName('Eddie'),
            new LastName('Van Halen'),
            new BirthDate(new \DateTimeImmutable('1993-11-05')),
            new CreatedAt(new \DateTimeImmutable())
        );
    }
}
