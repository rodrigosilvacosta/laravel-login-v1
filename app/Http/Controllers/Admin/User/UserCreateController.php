<?php

namespace App\Http\Controllers\Admin\User;

use App\Application\User\UseCases\RegisterUser\RegisterUserUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\PostUserRequest;
use Symfony\Component\HttpFoundation\Response;

class UserCreateController extends Controller
{
    public function __construct(private RegisterUserUseCase $useCase) {}

    public function __invoke(PostUserRequest $request): Response
    {
        $outputDto = $this->useCase->execute($request->toDto());

        return response()->json($outputDto->toArray(), Response::HTTP_CREATED);
    }
}
