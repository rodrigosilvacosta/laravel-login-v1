<?php

namespace Tests\Feature\Http\Controllers\Admin\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;
use Tests\Helpers\Feature\SanctumUserCreatorTrait;
use Tests\TestCase;

class UserUpdateControllerTest extends TestCase
{
    use RefreshDatabase;
    use SanctumUserCreatorTrait;

    private const URI_TEST = 'api/admin/users';

    public function test_user_update_controller_success(): void
    {
        $this->createValidAdminUser();

        $newName = 'UpdatedName';
        $newLastName = 'UpdatedLast';

        $user = User::factory()->create([
            'first_name' => 'User',
            'last_name' => 'One',
            'email' => 'email@example.com',
            'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        ]);

        $data = [
            'first_name' => $newName,
            'last_name' => $newLastName,
            'uuid' => $user->uuid,
        ];

        $response = $this->putJson(self::URI_TEST, $data);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('users', [
            'uuid' => $user->uuid,
            'first_name' => $newName,
            'last_name' => $newLastName,
        ]);
        $response->assertJson([
            'uuid' => $user->uuid,
            'first_name' => $newName,
            'last_name' => $newLastName,
        ]);
    }

    public function test_user_update_controller_not_authenticated(): void
    {
        $token = 'Bearer invalid_token';

        $data = [
            'first_name' => 'UpdatedName',
            'last_name' => 'UpdatedLast',
            'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        ];

        $response = $this->putJson(self::URI_TEST, $data, ['Authorization' => $token]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    #[DataProvider('invalid_request_data_provider')]
    public function test_user_update_controller_invalid_request(
        array $data,
        array $expectedErrors
    ): void {
        $this->createValidAdminUser();

        $response = $this->putJson(self::URI_TEST, $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors($expectedErrors);
    }

    public static function invalid_request_data_provider(): array
    {
        return [
            'empty request' => [
                'data' => [],
                'expectedErrors' => [
                    'first_name',
                    'last_name',
                    'uuid',
                ],
            ],
            'empty fields' => [
                'data' => [
                    'first_name' => '',
                    'last_name' => '',
                    'uuid' => '',
                ],
                'expectedErrors' => [
                    'first_name',
                    'last_name',
                    'uuid',
                ],
            ],
            'short fields' => [
                'data' => [
                    'first_name' => 'a',
                    'last_name' => 'b',
                    'uuid' => 'invalid-uuid',
                ],
                'expectedErrors' => [
                    'first_name',
                    'last_name',
                    'uuid',
                ],
            ],
            'long fields' => [
                'data' => [
                    'first_name' => str_repeat('a', 46),
                    'last_name' => str_repeat('b', 46),
                    'uuid' => '123e4567-e89b-12d3-a456-426614174000',
                ],
                'expectedErrors' => [
                    'first_name',
                    'last_name',
                ],
            ],
            'invalid uuid' => [
                'data' => [
                    'first_name' => 'ValidName',
                    'last_name' => 'ValidLast',
                    'uuid' => 'not-a-uuid',
                ],
                'expectedErrors' => [
                    'uuid',
                ],
            ],
        ];
    }
}
