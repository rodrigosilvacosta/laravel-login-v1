<?php

namespace Tests\Helpers\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

trait SanctumUserCreatorTrait
{
    private function createValidAdminUser(): User
    {
        $adminUser = User::factory()->create();

        /** com isso não precisa enviar o token no header para o test */
        Sanctum::actingAs($adminUser);

        return $adminUser;
    }
}
