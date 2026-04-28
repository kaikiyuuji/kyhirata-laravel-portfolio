<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\SocialLinkRepositoryInterface;
use App\Http\Requests\Admin\SocialLink\StoreSocialLinkRequest;
use App\Http\Requests\Admin\SocialLink\UpdateSocialLinkRequest;
use App\Actions\Admin\SocialLink\CreateSocialLinkAction;
use App\Actions\Admin\SocialLink\UpdateSocialLinkAction;
use App\Actions\Admin\SocialLink\DeleteSocialLinkAction;

class SocialLinkController extends Controller
{
    public function __construct(protected SocialLinkRepositoryInterface $repository) {}

    public function index()
    {
        return response()->json($this->repository->all());
    }

    public function create()
    {
        return response()->json([]);
    }

    public function store(StoreSocialLinkRequest $request, CreateSocialLinkAction $action)
    {
        $socialLink = $action->execute($request->validated());
        return response()->json(['data' => $socialLink], 201);
    }

    public function edit(int $id)
    {
        return response()->json($this->repository->findById($id));
    }

    public function update(UpdateSocialLinkRequest $request, UpdateSocialLinkAction $action, int $id)
    {
        $socialLink = $action->execute($id, $request->validated());
        return response()->json(['data' => $socialLink]);
    }

    public function destroy(DeleteSocialLinkAction $action, int $id)
    {
        $action->execute($id);
        return response()->json(['message' => 'Deletado com sucesso.']);
    }
}
