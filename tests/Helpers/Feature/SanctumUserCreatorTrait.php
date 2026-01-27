<?php

namespace Tests\Helpers\Feature;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Laravel\Sanctum\Sanctum;

trait SanctumUserCreatorTrait
{
    private function createValidAdminUser(?User $adminUser = null): User
    {
        if (null === $adminUser) {
            $adminUser = User::factory()->createOne();
        }

        /** com isso não precisa enviar o token no header para o test */
        Sanctum::actingAs($adminUser);

        return $adminUser;
    }
}
