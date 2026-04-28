<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Contracts\TechnologyRepositoryInterface;
use App\Http\Requests\Admin\Project\StoreProjectRequest;
use App\Http\Requests\Admin\Project\UpdateProjectRequest;
use App\Actions\Admin\Project\CreateProjectAction;
use App\Actions\Admin\Project\UpdateProjectAction;
use App\Actions\Admin\Project\DeleteProjectAction;
use App\Actions\Admin\Project\ToggleProjectButtonAction;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectRepositoryInterface $repository,
        protected TechnologyRepositoryInterface $techRepository,
    ) {}

    public function index()
    {
        $projects = $this->repository->all();

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $technologies = $this->techRepository->all();

        return view('admin.projects.create', compact('technologies'));
    }

    public function store(StoreProjectRequest $request, CreateProjectAction $action)
    {
        $action->execute($request->validated(), $request->file('thumbnail'));

        return redirect()->route('admin.projects.index')
            ->with('success', 'Projeto criado com sucesso.');
    }

    public function edit(int $id)
    {
        $project = $this->repository->findById($id);
        $technologies = $this->techRepository->all();

        return view('admin.projects.edit', compact('project', 'technologies'));
    }

    public function update(UpdateProjectRequest $request, UpdateProjectAction $action, int $id)
    {
        $action->execute($id, $request->validated(), $request->file('thumbnail'));

        return redirect()->route('admin.projects.index')
            ->with('success', 'Projeto atualizado com sucesso.');
    }

    public function destroy(DeleteProjectAction $action, int $id)
    {
        $action->execute($id);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Projeto removido com sucesso.');
    }

    public function toggle(ToggleProjectButtonAction $action, int $id)
    {
        $action->execute($id);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Visibilidade do botão alterada.');
    }
}
