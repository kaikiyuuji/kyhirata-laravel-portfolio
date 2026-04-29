@extends('layouts.admin')

@section('title', 'Links Sociais')

@section('content')
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
    <div>
        <h3 class="text-lg font-bold text-slate-800">Todos os Links Sociais</h3>
        <p class="text-sm text-slate-500 mt-0.5">Gerencie suas redes sociais do portfólio</p>
    </div>
    <a href="{{ route('admin.social-links.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl shadow-sm text-sm font-semibold transition hover:-translate-y-0.5">
        <i class="fas fa-plus text-xs"></i> Novo Link
    </a>
</div>

<!-- Desktop Table -->
<div class="hidden md:block admin-card bg-white rounded-2xl overflow-hidden">
    <table class="min-w-full">
        <thead>
            <tr class="border-b border-slate-100">
                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Plataforma</th>
                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">URL</th>
                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Ícone</th>
                <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($socialLinks as $link)
            <tr class="hover:bg-slate-50/60 transition-colors">
                <td class="px-6 py-4 font-semibold text-slate-900">{{ $link->platform }}</td>
                <td class="px-6 py-4">
                    <a href="{{ $link->url }}" target="_blank" class="text-blue-600 hover:underline text-sm truncate max-w-xs block">{{ $link->url }}</a>
                </td>
                <td class="px-6 py-4">
                    <div class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center text-xl text-slate-700">
                        <i class="{{ $link->display_icon }}"></i>
                    </div>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="inline-flex items-center gap-1">
                        <a href="{{ route('admin.social-links.edit', $link->id) }}" class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 transition" title="Editar">
                            <i class="fas fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.social-links.destroy', $link->id) }}" method="POST" class="inline" onsubmit="return confirm('Confirmar exclusão?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 rounded-lg text-red-500 hover:bg-red-50 transition" title="Excluir">
                                <i class="fas fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                    <i class="fas fa-share-nodes text-3xl mb-2 block"></i>
                    Nenhum link social cadastrado.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Mobile Cards -->
<div class="md:hidden space-y-3">
    @forelse($socialLinks as $link)
    <div class="admin-card bg-white rounded-2xl p-4">
        <div class="flex items-center gap-3 mb-3">
            <div class="h-12 w-12 rounded-xl bg-slate-100 flex items-center justify-center text-2xl text-slate-700 shrink-0">
                <i class="{{ $link->display_icon }}"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="font-semibold text-slate-900">{{ $link->platform }}</p>
                <a href="{{ $link->url }}" target="_blank" class="text-sm text-blue-600 hover:underline truncate block">{{ $link->url }}</a>
            </div>
        </div>
        <div class="flex items-center gap-2 pt-3 border-t border-slate-100">
            <a href="{{ route('admin.social-links.edit', $link->id) }}" class="flex-1 text-center py-2 rounded-lg text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 transition">Editar</a>
            <form action="{{ route('admin.social-links.destroy', $link->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Confirmar exclusão?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-2 rounded-lg text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">Excluir</button>
            </form>
        </div>
    </div>
    @empty
    <div class="admin-card bg-white rounded-2xl p-8 text-center text-slate-400">
        <i class="fas fa-share-nodes text-3xl mb-2 block"></i>
        Nenhum link social cadastrado.
    </div>
    @endforelse
</div>
@endsection
