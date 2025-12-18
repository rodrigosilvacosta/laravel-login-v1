<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserLogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        /**
         * LOGOUT
         * realiza o logout do usuário invalidando o token atual
         *
         * @todo criar o Use Case para logout
         */
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
