<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Response;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;

class CustomLogoutResponse implements LogoutResponseContract
{
    public function toResponse($request)
    {
        $user = auth('sanctum')->user();

        // Se estiver autenticado via Sanctum token, remove o token atual
        if ($user && method_exists($user, 'currentAccessToken') && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        auth('sanctum')->forgetUser();

        return Response::json(['message' => 'Sessão encerrada.'], 200);
    }
}
