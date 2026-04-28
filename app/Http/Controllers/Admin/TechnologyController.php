<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\TechnologyRepositoryInterface;
use App\Http\Requests\Admin\Technology\StoreTechnologyRequest;
use App\Http\Requests\Admin\Technology\UpdateTechnologyRequest;
use App\Actions\Admin\Technology\CreateTechnologyAction;
use App\Actions\Admin\Technology\UpdateTechnologyAction;
use App\Actions\Admin\Technology\DeleteTechnologyAction;

class TechnologyController extends Controller
{
    public function __construct(protected TechnologyRepositoryInterface $repository) {}

    public function index()
    {
        return response()->json($this->repository->all());
    }

    public function create()
    {
        return response()->json([]);
    }

    public function store(StoreTechnologyRequest $request, CreateTechnologyAction $action)
    {
        $technology = $action->execute($request->validated());
        return response()->json(['data' => $technology], 201);
    }

    public function edit(int $id)
    {
        return response()->json($this->repository->findById($id));
    }

    public function update(UpdateTechnologyRequest $request, UpdateTechnologyAction $action, int $id)
    {
        $technology = $action->execute($id, $request->validated());
        return response()->json(['data' => $technology]);
    }

    public function destroy(DeleteTechnologyAction $action, int $id)
    {
        $action->execute($id);
        return response()->json(['message' => 'Deletado com sucesso.']);
    }
}
