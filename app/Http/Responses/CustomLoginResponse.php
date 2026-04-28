<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Response;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class CustomLoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $token = $request->user()->createToken('admin-token')->plainTextToken;

        return Response::json([
            'token' => $token,
            'expires_at' => now()->addMinutes(config('sanctum.expiration', 120))->toDateTimeString(),
            'user' => $request->user(),
        ]);
    }
}
