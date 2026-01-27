<?php

namespace App\Http\Controllers\Admin\User;

use App\Application\User\UseCases\UserFindByUuidUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserGetByUuidRequest;
use Illuminate\Http\JsonResponse;

class UserFindByUuidController extends Controller
{
    public function __construct(private UserFindByUuidUseCase $useCase) {}

    public function __invoke(UserGetByUuidRequest $request): JsonResponse
    {
        $dto = $request->toDto();
        $outputDto = $this->useCase->execute($dto);

        return response()->json($outputDto->toArray());
    }
}
