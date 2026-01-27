<?php

namespace Tests\Feature\Http\Controllers\Admin\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;
use Tests\Helpers\Feature\SanctumUserCreatorTrait;
use Tests\TestCase;

class UserRegisterControllerTest extends TestCase
{
    use RefreshDatabase;
    use SanctumUserCreatorTrait;

    private const URI_TEST = 'api/admin/users';

    public function test_user_register_controller_success(): void
    {
        $this->createValidAdminUser();

        $data = [
            'first_name' => 'José',
            'last_name' => 'Doe',
            'email' => 'jose.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson(self::URI_TEST, $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('users', [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);
        $response->assertJsonStructure(['uuid']);
        $this->assertIsString($response->json('uuid'));
    }

    public function test_user_register_controller_not_authenticated(): void
    {
        $token = 'Bearer invalid_token';

        $data = [
            'first_name' => 'José',
            'last_name' => 'Doe',
            'email' => 'jose.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson(self::URI_TEST, $data, ['Authorization' => $token]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    #[DataProvider('invalid_request_data_provider')]
    public function test_user_register_controller_invalid_request(
        array $data,
        array $expectedErrors
    ): void {
        $this->createValidAdminUser();

        $response = $this->postJson(self::URI_TEST, $data);

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
                    'email',
                    'password',
                ],
            ],
            'empty fields' => [
                'data' => [
                    'first_name' => '',
                    'last_name' => '',
                    'email' => '',
                    'password' => '',
                    'password_confirmation' => '',
                ],
                'expectedErrors' => [
                    'first_name',
                    'last_name',
                    'email',
                    'password',
                ],
            ],
            'short fields' => [
                'data' => [
                    'first_name' => 'a',
                    'last_name' => 'b',
                    'email' => 'c@',
                    'password' => '123',
                    'password_confirmation' => '456',
                ],
                'expectedErrors' => [
                    'first_name',
                    'last_name',
                    'email',
                    'password',
                ],
            ],
            'long fields' => [
                'data' => [
                    'first_name' => str_repeat('a', 46),
                    'last_name' => str_repeat('b', 46),
                    'email' => str_repeat('c', 245).'@example.com',
                    'password' => str_repeat('1', 65),
                    'password_confirmation' => str_repeat('1', 65),
                ],
                'expectedErrors' => [
                    'first_name',
                    'last_name',
                    'email',
                    'password',
                ],
            ],
        ];
    }
}
