<?php

namespace Tests\Feature\Http\Controllers\Admin\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class UserLoginContollerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_login_controller(): void
    {
        $password = 'password';
        $deviceName = 'device_name';
        $user = User::factory()->create([
            'first_name' => 'User',
            'last_name' => 'One',
            'email' => 'email@example.com',
            'password' => Hash::make($password),
        ]);

        $data = [
            'email' => $user->email,
            'password' => $password,
            'device_name' => $deviceName,
        ];

        $response = $this->post('api/admin/login', $data);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
        $this->assertIsString($response->json('token'));
    }

    #[DataProvider('invalid_request_data_provider')]
    public function test_user_login_controller_fail(
        array $data,
        array $expectedErrors,
        array $unexpectedErrors
    ): void {
        $response = $this->post('api/admin/login', $data);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors($expectedErrors);
        $response->assertJsonMissingValidationErrors($unexpectedErrors);
    }

    public static function invalid_request_data_provider(): array
    {
        return [
            [
                'data' => [],
                'expectedErrors' => [
                    'email',
                    'device_name',
                    'password',
                ],
                'unexpectedErrors' => []
            ],
            [
                'data' => [
                    'email' => '',
                    'password' => '',
                    'device_name' => '',
                ],
                'expectedErrors' => [
                    'email',
                    'device_name',
                    'password',
                ],
                'unexpectedErrors' => []
            ],
            [
                'data' => [
                    'email' => 'email@com',
                    'password' => 'passwor',
                    'device_name' => '',
                ],
                'expectedErrors' => [
                    'email',
                    'device_name',
                    'password',
                ],
                'unexpectedErrors' => []
            ],
            [
                'data' => [
                    'email' => 'email',
                    'password' => str_repeat('a', 66),
                    'device_name' => str_repeat('a', 66),
                ],
                'expectedErrors' => [
                    'email',
                    'device_name',
                    'password',
                ],
                'unexpectedErrors' => []
            ]
        ];
    }
}
