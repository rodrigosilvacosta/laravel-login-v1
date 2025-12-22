<?php

namespace Tests\Feature\Http\Controllers\Admin\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
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
}
