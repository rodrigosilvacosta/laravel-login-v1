<?php

namespace App\Http\Controllers\Admin\User;

use App\Application\User\UseCases\LoginUser\LoginUserUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserLoginRequest;

class UserLoginController extends Controller
{
    public function __construct(private LoginUserUseCase $loginUserUseCase) {}
    /**
     * Handle the incoming request.
     */
    public function __invoke(UserLoginRequest $request)
    {
        $dto = $request->toDto();
        $outputDto = $this->loginUserUseCase->execute($dto);

        return response()->json($outputDto->toArray());
    }
}
