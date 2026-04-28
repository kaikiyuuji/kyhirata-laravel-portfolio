<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ExperienceRepositoryInterface;
use App\Http\Requests\Admin\Experience\StoreExperienceRequest;
use App\Http\Requests\Admin\Experience\UpdateExperienceRequest;
use App\Actions\Admin\Experience\CreateExperienceAction;
use App\Actions\Admin\Experience\UpdateExperienceAction;
use App\Actions\Admin\Experience\DeleteExperienceAction;

class ExperienceController extends Controller
{
    public function __construct(protected ExperienceRepositoryInterface $repository) {}

    public function index()
    {
        $experiences = $this->repository->all();

        return view('admin.experiences.index', compact('experiences'));
    }

    public function create()
    {
        return view('admin.experiences.create');
    }

    public function store(StoreExperienceRequest $request, CreateExperienceAction $action)
    {
        $action->execute($request->validated());

        return redirect()->route('admin.experiences.index')
            ->with('success', 'Experiência criada com sucesso.');
    }

    public function edit(int $id)
    {
        $experience = $this->repository->findById($id);

        return view('admin.experiences.edit', compact('experience'));
    }

    public function update(UpdateExperienceRequest $request, UpdateExperienceAction $action, int $id)
    {
        $action->execute($id, $request->validated());

        return redirect()->route('admin.experiences.index')
            ->with('success', 'Experiência atualizada com sucesso.');
    }

    public function destroy(DeleteExperienceAction $action, int $id)
    {
        $action->execute($id);

        return redirect()->route('admin.experiences.index')
            ->with('success', 'Experiência removida com sucesso.');
    }
}
