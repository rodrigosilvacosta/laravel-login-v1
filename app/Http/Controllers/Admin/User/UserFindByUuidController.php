<?php

namespace App\Http\Controllers\Admin\User;

use App\Application\User\UseCases\FindUser\FindUserByUuidUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\GetUserByUuidRequest;

class UserFindByUuidController extends Controller
{
    public function __construct(private FindUserByUuidUseCase $useCase) {}

    public function __invoke(GetUserByUuidRequest $request)
    {
        $dto = $request->toDto();
        $outputDto = $this->useCase->execute($dto);

        return response()->json($outputDto->toArray());
    }
}
