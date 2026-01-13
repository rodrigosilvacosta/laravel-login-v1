<?php

namespace App\Http\Controllers\Admin\User;

use App\Application\User\UseCases\UserRegisterUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserRegisterRequest;
use Symfony\Component\HttpFoundation\Response;

class UserRegisterController extends Controller
{
    public function __construct(private UserRegisterUseCase $useCase) {}

    public function __invoke(UserRegisterRequest $request): Response
    {
        $outputDto = $this->useCase->execute($request->toDto());

        return response()->json($outputDto->toArray(), Response::HTTP_CREATED);
    }
}
