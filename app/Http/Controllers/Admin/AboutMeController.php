<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AboutMe\UpdateAboutMeRequest;
use App\Actions\Admin\AboutMe\UpdateAboutMeAction;
use App\Repositories\Contracts\AboutMeRepositoryInterface;

class AboutMeController extends Controller
{
    public function edit(AboutMeRepositoryInterface $repository)
    {
        return response()->json($repository->get());
    }

    public function update(UpdateAboutMeRequest $request, UpdateAboutMeAction $action)
    {
        $aboutMe = $action->execute($request->validated());
        
        return response()->json([
            'message' => 'About Me atualizado com sucesso.', 
            'data' => $aboutMe
        ]);
    }
}
