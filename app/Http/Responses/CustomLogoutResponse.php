<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Response;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;

class CustomLogoutResponse implements LogoutResponseContract
{
    public function toResponse($request)
    {
        return redirect('/');
    }
}
