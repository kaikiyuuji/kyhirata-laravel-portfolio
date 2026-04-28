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
        $technologies = $this->repository->all();

        return view('admin.technologies.index', compact('technologies'));
    }

    public function create()
    {
        return view('admin.technologies.create');
    }

    public function store(StoreTechnologyRequest $request, CreateTechnologyAction $action)
    {
        $action->execute($request->validated());

        return redirect()->route('admin.technologies.index')
            ->with('success', 'Tecnologia criada com sucesso.');
    }

    public function edit(int $id)
    {
        $technology = $this->repository->findById($id);

        return view('admin.technologies.edit', compact('technology'));
    }

    public function update(UpdateTechnologyRequest $request, UpdateTechnologyAction $action, int $id)
    {
        $action->execute($id, $request->validated());

        return redirect()->route('admin.technologies.index')
            ->with('success', 'Tecnologia atualizada com sucesso.');
    }

    public function destroy(DeleteTechnologyAction $action, int $id)
    {
        $action->execute($id);

        return redirect()->route('admin.technologies.index')
            ->with('success', 'Tecnologia removida com sucesso.');
    }
}
