<?php

namespace App\Http\Controllers\Admin\User;

use App\Application\User\Dtos\Inputs\UserGetCurrentProfileInputDto;
use App\Application\User\UseCases\UserGetCurrentProfileUseCase;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserGetProfileController extends Controller
{
    public function __construct(private UserGetCurrentProfileUseCase $useCase) {}

    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            $outputDto = $this->useCase->execute(UserGetCurrentProfileInputDto::createFrom(
                uuid: $user->uuid,
                firstName: $user->first_name,
                lastName: $user->last_name,
                email: $user->email,
            ));
        }

        $output = $outputDto ? $outputDto->toArray() : [];

        return response()->json($output);
    }
}
