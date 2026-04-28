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
        $aboutMe = $repository->get();

        return view('admin.about.edit', compact('aboutMe'));
    }

    public function update(UpdateAboutMeRequest $request, UpdateAboutMeAction $action)
    {
        $action->execute($request->validated());

        return redirect()->route('admin.about.edit')
            ->with('success', 'Informações atualizadas com sucesso.');
    }
}
