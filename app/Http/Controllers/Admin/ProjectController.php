<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Http\Requests\Admin\Project\StoreProjectRequest;
use App\Http\Requests\Admin\Project\UpdateProjectRequest;
use App\Actions\Admin\Project\CreateProjectAction;
use App\Actions\Admin\Project\UpdateProjectAction;
use App\Actions\Admin\Project\DeleteProjectAction;
use App\Actions\Admin\Project\ToggleProjectButtonAction;

class ProjectController extends Controller
{
    public function __construct(protected ProjectRepositoryInterface $repository) {}

    public function index()
    {
        return response()->json($this->repository->all());
    }

    public function create()
    {
        return response()->json([]);
    }

    public function store(StoreProjectRequest $request, CreateProjectAction $action)
    {
        $project = $action->execute($request->validated());
        return response()->json(['data' => $project], 201);
    }

    public function edit(int $id)
    {
        return response()->json($this->repository->findById($id));
    }

    public function update(UpdateProjectRequest $request, UpdateProjectAction $action, int $id)
    {
        $project = $action->execute($id, $request->validated());
        return response()->json(['data' => $project]);
    }

    public function destroy(DeleteProjectAction $action, int $id)
    {
        $action->execute($id);
        return response()->json(['message' => 'Deletado com sucesso.']);
    }

    public function toggle(ToggleProjectButtonAction $action, int $id)
    {
        $project = $action->execute($id);
        return response()->json(['message' => 'Botão alternado.', 'data' => $project]);
    }
}
