<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        /**
         * Profile *talvez não será necessário
         *
         * @todo criar o Use Case
         */
        return response()->json($request->user());
    }
}
