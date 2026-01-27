<?php

namespace App\Http\Controllers\Admin\User;

use App\Application\User\UseCases\UserLoginUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserLoginRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserLoginController extends Controller
{
    public function __construct(private UserLoginUseCase $userLoginUseCase) {}
    /**
     * Handle the incoming request.
     */
    public function __invoke(UserLoginRequest $request): JsonResponse
    {
        $dto = $request->toDto();
        $outputDto = $this->userLoginUseCase->execute($dto);

        return response()->json($outputDto->toArray(), Response::HTTP_OK);
    }
}
