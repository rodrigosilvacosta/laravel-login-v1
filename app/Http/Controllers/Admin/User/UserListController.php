<?php

namespace App\Http\Controllers\Admin\User;

use App\Application\User\UseCases\UserListUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserListRequest;
use Illuminate\Http\JsonResponse;

class UserListController extends Controller
{
    public function __construct(private UserListUseCase $useCase) {}

    public function __invoke(UserListRequest $request): JsonResponse
    {
        $dto = $request->toDto();
        $outputDto = $this->useCase->execute($dto);

        return response()->json($outputDto->toArray());
    }
}
