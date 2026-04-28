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
        $socialLinks = $this->repository->all();

        return view('admin.social-links.index', compact('socialLinks'));
    }

    public function create()
    {
        return view('admin.social-links.create');
    }

    public function store(StoreSocialLinkRequest $request, CreateSocialLinkAction $action)
    {
        $action->execute($request->validated());

        return redirect()->route('admin.social-links.index')
            ->with('success', 'Link social criado com sucesso.');
    }

    public function edit(int $id)
    {
        $socialLink = $this->repository->findById($id);

        return view('admin.social-links.edit', compact('socialLink'));
    }

    public function update(UpdateSocialLinkRequest $request, UpdateSocialLinkAction $action, int $id)
    {
        $action->execute($id, $request->validated());

        return redirect()->route('admin.social-links.index')
            ->with('success', 'Link social atualizado com sucesso.');
    }

    public function destroy(DeleteSocialLinkAction $action, int $id)
    {
        $action->execute($id);

        return redirect()->route('admin.social-links.index')
            ->with('success', 'Link social removido com sucesso.');
    }
}
