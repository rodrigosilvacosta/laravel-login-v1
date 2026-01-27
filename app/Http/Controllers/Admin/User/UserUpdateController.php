<?php

namespace App\Http\Controllers\Admin\User;

use App\Application\User\UseCases\UserUpdateUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserUpdateRequest;
use Illuminate\Http\JsonResponse;

class UserUpdateController extends Controller
{
    public function __construct(private UserUpdateUseCase $useCase) {}

    public function __invoke(UserUpdateRequest $request): JsonResponse
    {
        $dto = $request->toDto();
        $outputDto = $this->useCase->execute($dto);

        return response()->json($outputDto->toArray());
    }
}
