<?php
namespace App\Tests\Infrastructure\Controller\Trait;

use App\Infrastructure\Service\UuidGenerator;

trait AnnouncementPayloadTrait
{
    protected function createAnnouncementPayload(array $overrides = []): array
    {
        $defaultPayload = [
            'content'      => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptate totam odio quis nesciunt natus consequuntur reprehenderit quos dolorum libero doloremque, cupiditate culpa quia sapiente, nihil officiis commodi? Iusto, animi in.',
            'userId'       => (new UuidGenerator())->generate(),
            'startingDate' => '2025-05-05',
            'location'     => 'Rue de Rivotest',
            'city'         => 'Paris',
            'postcode'     => '75000',
            'latitude'     => 0.85,
            'longitude'    => 0.85,
            'petIds'       => [(new UuidGenerator())->generate()],
            'type'         => 1,
        ];

        return array_merge($defaultPayload, $overrides);
    }
}
