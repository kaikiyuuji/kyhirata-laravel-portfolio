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
        return response()->json($this->repository->all());
    }

    public function create()
    {
        return response()->json([]);
    }

    public function store(StoreExperienceRequest $request, CreateExperienceAction $action)
    {
        $experience = $action->execute($request->validated());
        return response()->json(['data' => $experience], 201);
    }

    public function edit(int $id)
    {
        return response()->json($this->repository->findById($id));
    }

    public function update(UpdateExperienceRequest $request, UpdateExperienceAction $action, int $id)
    {
        $experience = $action->execute($id, $request->validated());
        return response()->json(['data' => $experience]);
    }

    public function destroy(DeleteExperienceAction $action, int $id)
    {
        $action->execute($id);
        return response()->json(['message' => 'Deletado com sucesso.']);
    }
}
