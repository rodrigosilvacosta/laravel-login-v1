<?php

namespace Tests\Feature\Http\Controllers\Admin\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;
use Tests\Helpers\Feature\SanctumUserCreatorTrait;
use Tests\TestCase;

class UserListControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use SanctumUserCreatorTrait;

    private const URI_TEST = 'api/admin/users';

    public function test_user_list_controller_unauthorized(): void
    {
        $response = $this->getJson(self::URI_TEST);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_user_list_controller_success(): void
    {
        $usersTotal = 21;
        $users = User::factory()->count($usersTotal)->create();
        $this->createValidAdminUser($users->first());
        $page = 1;
        $perPage = 10;
        $queryParams = [
            'page' => $page,
            'per_page' => $perPage,
        ];
        $response = $this->getJson(self::URI_TEST . '?' . http_build_query($queryParams));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'users' => [
                '*' => [
                    'uuid',
                    'first_name',
                    'last_name',
                    'email',
                ]
            ],
            'last_page',
            'total',
            'current_page',
            'per_page',
        ]);

        $response->assertJson([
            'last_page' => (int) ceil($usersTotal / $perPage),
            'total' => $usersTotal,
            'current_page' => $page,
            'per_page' => $perPage,
        ]);
        $response->assertJsonCount($perPage, 'users');
    }

    #[DataProvider('pagination_data_provider')]
    public function test_user_list_controller_pagination(
        int $page,
        int $perPage,
        int $totalUsers,
        int $expectedUsers
    ): void {
        $users = User::factory()->count($totalUsers)->create();
        $this->createValidAdminUser($users->first());

        $queryParams = [
            'page' => $page,
            'per_page' => $perPage,
        ];

        $response = $this->getJson(self::URI_TEST . '?' . http_build_query($queryParams));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'last_page' => (int) ceil($totalUsers / $perPage),
            'total' => $totalUsers,
            'current_page' => $page,
            'per_page' => $perPage,
        ]);
        $this->assertCount($expectedUsers, $response->json('users'));
    }

    #[DataProvider('filters_data_provider')]
    public function test_user_list_controller_filters(
        array $dataUsers,
        array $filteredUsers,
        array $expectedUsers
    ): void {
        $users = collect($dataUsers)
            ->map(fn($data) => User::factory()->create($data));

        $this->createValidAdminUser($users->first());

        $queryParams = array_merge([
            'page' => 1,
            'per_page' => 100,
        ], $filteredUsers);

        $response = $this->getJson(self::URI_TEST . '?' . http_build_query($queryParams));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(count($expectedUsers), 'users');
        $response->assertJson(['users' => $expectedUsers]);
    }

    #[DataProvider('invalid_params_data_provider')]
    public function test_user_list_controller_validation_error(array $queryParams, array $expectedErrors): void
    {
        $this->createValidAdminUser();

        $response = $this->getJson(self::URI_TEST . '?' . http_build_query($queryParams));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors($expectedErrors);
    }

    public static function invalid_params_data_provider(): array
    {
        return [
            'missing required fields' => [
                [],
                ['page', 'per_page']
            ],
            'page not integer' => [
                ['page' => 'not-int', 'per_page' => 10],
                ['page']
            ],
            'page less than 1' => [
                ['page' => 0, 'per_page' => 10],
                ['page']
            ],
            'per_page less than 1' => [
                ['page' => 1, 'per_page' => 0],
                ['per_page']
            ],
            'per_page greater than 100' => [
                ['page' => 1, 'per_page' => 101],
                ['per_page']
            ],
        ];
    }

    public static function pagination_data_provider(): array
    {
        return [
            'page 1' => [
                'page' => 1,
                'perPage' => 5,
                'totalUsers' => 16,
                'expectedUsers' => 5
            ],
            'page 2' => [
                'page' => 2,
                'perPage' => 5,
                'totalUsers' => 16,
                'expectedUsers' => 5
            ],
            'page 4' => [
                'page' => 4,
                'perPage' => 5,
                'totalUsers' => 16,
                'expectedUsers' => 1
            ],
        ];
    }

    public static function filters_data_provider(): array
    {
        $dataUsers = [
            [
                'first_name' => 'Roberto',
                'last_name' => 'Zezé',
                'email' => 'roberto@zeze.com',
            ],
            [
                'first_name' => 'Xyyz',
                'last_name' => 'Beta',
                'email' => 'xyyz@beta.com',
            ],
            [
                'first_name' => 'Kathyy',
                'last_name' => 'Beta',
                'email' => 'kathyy@beta.com',
            ],
        ];

        return [
            'first_name' => [
                'dataUsers' => $dataUsers,
                'filteredUsers' => ['first_name' => 'Rob'],
                'expectedUsers' => [$dataUsers[0]],
            ],
            'first_name' => [
                'dataUsers' => $dataUsers,
                'filteredUsers' => ['first_name' => 'YY'],
                'expectedUsers' => [$dataUsers[1], $dataUsers[2]],
            ],
            'last_name' => [
                'dataUsers' => $dataUsers,
                'filteredUsers' => ['last_name' => 'Ze'],
                'expectedUsers' => [$dataUsers[0]],
            ],
            'last_name multiple results' => [
                'dataUsers' => $dataUsers,
                'filteredUsers' => ['last_name' => 'Beta'],
                'expectedUsers' => [$dataUsers[1], $dataUsers[2]],
            ],
            'email' => [
                'dataUsers' => $dataUsers,
                'filteredUsers' => ['email' => 'zez'],
                'expectedUsers' => [$dataUsers[0]],
            ],
            'email multiple results' => [
                'dataUsers' => $dataUsers,
                'filteredUsers' => ['email' => 'eta'],
                'expectedUsers' => [$dataUsers[1], $dataUsers[2]],
            ],
            'multiple filters' => [
                'dataUsers' => $dataUsers,
                'filteredUsers' => ['first_name' => 'Rob', 'last_name' => 'eta', 'email' => '.com'],
                'expectedUsers' => [],
            ],
            'nullable filters' => [
                'dataUsers' => $dataUsers,
                'filteredUsers' => ['first_name' => null, 'last_name' => null, 'email' => null],
                'expectedUsers' => $dataUsers,
            ],
        ];
    }
}
