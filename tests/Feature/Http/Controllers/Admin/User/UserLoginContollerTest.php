<?php

namespace Tests\Feature\Http\Controllers\Admin\User;

use App\Domain\Shared\Exceptions\AppDomainExceptionCodeEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserLoginContollerTest extends TestCase
{
    use RefreshDatabase;

    private const URI_TEST = 'api/admin/login';

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

        $response = $this->postJson(self::URI_TEST, $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['token']);
        $this->assertIsString($response->json('token'));
    }

    #[DataProvider('autentication_failure_data_provider')]
    public function test_user_login_controller_autentication_failure(
        string $validEmail,
        string $validPassword,
        string $emailProvided,
        string $passwordProvided
    ): void {

        $user = User::factory()->create([
            'first_name' => 'User',
            'last_name' => 'One',
            'email' => $validEmail,
            'password' => Hash::make($validPassword),
        ]);

        $data = [
            'email' => $emailProvided,
            'password' => $passwordProvided,
            'device_name' => 'device_name',
        ];

        $response = $this->postJson(self::URI_TEST, $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonMissing(['token']);
        $response->assertJsonStructure(['code', 'errors']);
        $response->assertJsonFragment([
            'code' => AppDomainExceptionCodeEnum::USER_AUTHENTICATION_FAILURE->value,
            'errors' => AppDomainExceptionCodeEnum::USER_AUTHENTICATION_FAILURE->getMessage(),
        ]);
    }

    #[DataProvider('invalid_request_data_provider')]
    public function test_user_login_controller_invalid_request(
        array $data,
        array $expectedErrors
    ): void {
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
                    'email',
                    'device_name',
                    'password',
                ],
            ],
            'empty fields' => [
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
            ],
            'invalid email, password and device name case 1' => [
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
            ],
            'invalid email, password and device name case 2' => [
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
            ]
        ];
    }

    public static function autentication_failure_data_provider(): array
    {
        // valid email: example@ema
        return [
            'incorrect email' => [
                'validEmail' => 'email@example.com',
                'validPassword' => 'password',
                'emailProvided' => 'invalid_email@example.com',
                'passwordProvided' => 'password',
            ],
            'incorrect password' => [
                'validEmail' => 'email@example.com',
                'validPassword' => 'password',
                'emailProvided' => 'email@example.com',
                'passwordProvided' => 'invalid_password',
            ],
        ];
    }
}
