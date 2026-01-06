<?php

namespace Tests\Feature\Http\Controllers\Admin\User;

use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;
use Tests\Helpers\Feature\SanctumUserCreatorTrait;
use Tests\TestCase;

class UserFindByUuidControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use SanctumUserCreatorTrait;

    private const URI_TEST = 'api/admin/users/uuid';

    public function test_user_find_by_uuid_controller(): void
    {
        $this->createValidAdminUser();
        $user = User::factory()->create();
        $response = $this->getJson(self::URI_TEST . '/' . $user->uuid);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'uuid',
            'first_name',
            'last_name',
            'email',
        ]);
        $response->assertJson([
            'uuid' => $user->uuid,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
        ]);
    }

    public function test_user_find_by_uuid_controller_not_found_uuid(): void
    {
        $this->createValidAdminUser();
        $user = User::factory()->create();
        $response = $this->getJson(self::URI_TEST . '/' . $this->faker->uuid());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure(['code', 'errors']);
        $response->assertJsonFragment([
            'code' => AppDomainExceptionCodeEnum::USER_NOT_FOUND->value,
            'errors' => AppDomainExceptionCodeEnum::USER_NOT_FOUND->getMessage(),
        ]);
    }

    #[DataProvider('invalid_uuid_data_provider')]
    public function test_user_find_by_uuid_controller_invalid_uuid(
        string $invalidUuid,
        array $expectedErrors
    ): void {
        $this->createValidAdminUser();
        $user = User::factory()->create();
        $response = $this->getJson(self::URI_TEST . '/' . $invalidUuid);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure(['message', 'errors']);
        $response->assertJsonValidationErrors($expectedErrors);
    }

    public static function invalid_uuid_data_provider(): array
    {
        return [
            'short uuid' => [
                'invalidUuid' => '1',
                'expectedErrors' => ['uuid']
            ],
            'invalid uuid format' => [
                'invalidUuid' => 'invalid-uuid-format-1234',
                'expectedErrors' => ['uuid']
            ],
        ];
    }
}
