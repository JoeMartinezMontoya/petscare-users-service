<?php
namespace App\Tests\Infrastructure\Repository;

use PHPUnit\Framework\Attributes\Group;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

#[Group('integration')]
#[Group('repository')]
class UserRepositoryTest extends KernelTestCase
{
    // private UserRepository $repository;

    // protected function setUp(): void
    // {
    //     parent::setUp();
    //     $this->repository = static::getContainer()->get(AnnouncementRepository::class);
    // }

    // public function testMustSaveAnnouncementInDatabase(): void
    // {
    //     $result = $this->repository->save(self::domainAnnouncement());
    //     $this->assertNotNull($result);
    // }

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

    // private static function domainAnnouncement(bool $valid = true): Announcement
    // {
    //     return new Announcement(
    //         $valid ? new Id((new UuidGenerator)->generate()) : null,
    //         null,
    //         $valid ? new Content('Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt doloremque alias odit totam. Ea, veniam accusantium mollitia id vero expedita dolores. Provident perspiciatis sed, tempore ad exercitationem et inventore ipsa?') : null,
    //         $valid ? new UserId((new UuidGenerator)->generate()) : null,
    //         $valid ? new StartingDate(new \DateTimeImmutable('2025-04-30')) : null,
    //         null,
    //         $valid ? new CreatedAt(new \DateTimeImmutable()) : null,
    //         null,
    //         $valid ? new Location('Lyon') : null,
    //         $valid ? new City('Lyon') : null,
    //         $valid ? new Postcode('69006') : null,
    //         $valid ? new Latitude(45.75) : null,
    //         $valid ? new Longitude(4.85) : null,
    //         $valid ? new PetId((new UuidGenerator)->generate()) : null,
    //         $valid ? new ViewsCount(0) : null,
    //         $valid ? new Type(1) : null,
    //         $valid ? new Status(1) : null
    //     );
    // }
}
