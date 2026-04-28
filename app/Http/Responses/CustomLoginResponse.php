<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Response;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class CustomLoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        return redirect()->intended(config('fortify.home'));
    }
}
