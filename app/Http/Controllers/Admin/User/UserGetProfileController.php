<?php

namespace App\Http\Controllers\Admin\User;

use App\Application\User\Dtos\Inputs\GetCurrentUserProfileInputDto;
use App\Application\User\UseCases\Profile\GetCurrentUserProfileUseCase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserGetProfileController extends Controller
{
    public function __construct(private GetCurrentUserProfileUseCase $useCase) {}

    public function __invoke(Request $request): Response
    {
        $user = $request->user();

        if ($user) {
            $outputDto = $this->useCase->execute(GetCurrentUserProfileInputDto::createFrom(
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
