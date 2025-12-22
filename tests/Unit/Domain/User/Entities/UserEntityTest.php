<?php

namespace Tests\Unit\Domain\User\Entities;

use App\Domain\User\Entities\UserEntity;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UserEntityTest extends TestCase
{
    public function test_user_entity_register(): void
    {
        $firstName = 'John';
        $lastName = 'Doe';
        $email = 'john.doe@example.com';

        $userEntity = UserEntity::register($firstName, $lastName, $email);

        $this->assertSame($firstName, (string) $userEntity->firstName->value);
        $this->assertSame($lastName, (string) $userEntity->lastName->value);
        $this->assertSame($email, (string) $userEntity->email->value);
        $this->assertNull($userEntity->id);
        $this->assertNotEmpty((string) $userEntity->uuid->value);
    }

    public function test_user_entity_create_from_primitives(): void
    {
        $id = 1;
        $uuid = '123e4567-e89b-12d3-a456-426614174000';
        $firstName = 'Jane';
        $lastName = 'Smith';
        $email = 'jane.smith@example.com';

        $userEntity = UserEntity::createFromPrimitives($id, $uuid, $firstName, $lastName, $email);

        $this->assertSame($id, $userEntity->id);
        $this->assertSame($uuid, $userEntity->uuid->value);
        $this->assertSame($firstName, $userEntity->firstName->value);
        $this->assertSame($lastName, $userEntity->lastName->value);
        $this->assertSame($email, $userEntity->email->value);
    }

    #[DataProvider('personal_info_data_provider')]
    public function test_user_entity_update_personal_info(
        string $oldFirstName,
        string $oldLastName,
        ?string $newFirstName,
        ?string $newLastName,
        string $expectedFirstName,
        string $expectedLastName
    ): void {
        $email = 'some@example.com';

        $userEntity = UserEntity::register($oldFirstName, $oldLastName, $email);
        $updatedUserEntity = $userEntity->updatePersonalInfo($newFirstName, $newLastName);

        $this->assertSame($expectedFirstName, $updatedUserEntity->firstName->value);
        $this->assertSame($expectedLastName, $updatedUserEntity->lastName->value);
        $this->assertSame($email, $updatedUserEntity->email->value);
    }

    public static function personal_info_data_provider(): array
    {
        $oldFirstName = 'OldFirstName';
        $oldLastName = 'OldLastName';
        $newFirstName = 'NewFirstName';
        $newLastName = 'NewLastName';

        return [
            [
                'oldFirstName' => $oldFirstName,
                'oldLastName' => $oldLastName,
                'newFirstName' => $newFirstName,
                'newLastName' => $newLastName,
                'expectedFirstName' => $newFirstName,
                'expectedLastName' => $newLastName
            ],
            [
                'oldFirstName' => $oldFirstName,
                'oldLastName' => $oldLastName,
                'newFirstName' => $newFirstName,
                'newLastName' => null,
                'expectedFirstName' => $newFirstName,
                'expectedLastName' => $oldLastName,
            ],
            [
                'oldFirstName' => $oldFirstName,
                'oldLastName' => $oldLastName,
                'newFirstName' => null,
                'newLastName' => $newLastName,
                'expectedFirstName' => $oldFirstName,
                'expectedLastName' => $newLastName,
            ],
            [
                'oldFirstName' => $oldFirstName,
                'oldLastName' => $oldLastName,
                'newFirstName' => null,
                'newLastName' => null,
                'expectedFirstName' => $oldFirstName,
                'expectedLastName' => $oldLastName,
            ],
        ];
    }
}
