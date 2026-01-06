<?php

namespace Tests\Feature\Http\Controllers\Admin\User;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\Helpers\Feature\SanctumUserCreatorTrait;
use Tests\TestCase;

class UserGetProfileControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use SanctumUserCreatorTrait;

    private const URI_TEST = 'api/admin/users/profile';

    public function test_user_get_profile_controller(): void
    {
        $user = $this->createValidAdminUser();

        $response = $this->getJson(self::URI_TEST);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson([
            'uuid' => $user->uuid,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
        ]);
    }
}
