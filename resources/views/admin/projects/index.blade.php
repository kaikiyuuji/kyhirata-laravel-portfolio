@extends('layouts.admin')

@section('title', 'Projetos')

@section('content')
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
    <div>
        <h3 class="text-lg font-bold text-slate-800">Todos os Projetos</h3>
        <p class="text-sm text-slate-500 mt-0.5">Gerencie seus projetos do portfólio</p>
    </div>
    <a href="{{ route('admin.projects.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl shadow-sm text-sm font-semibold transition hover:-translate-y-0.5">
        <i class="fas fa-plus text-xs"></i> Novo Projeto
    </a>
</div>

<!-- Desktop Table -->
<div class="hidden lg:block admin-card bg-white rounded-2xl overflow-hidden">
    <table class="min-w-full">
        <thead>
            <tr class="border-b border-slate-100">
                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Projeto</th>
                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tecnologias</th>
                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Visível</th>
                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Botão</th>
                <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($projects as $project)
            <tr class="hover:bg-slate-50/60 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        @if($project->thumbnail_path)
                            <img src="{{ Storage::url($project->thumbnail_path) }}" class="h-10 w-14 object-cover rounded-lg border border-slate-200 shrink-0">
                        @else
                            <div class="h-10 w-14 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 shrink-0"><i class="fas fa-image text-xs"></i></div>
                        @endif
                        <div class="min-w-0">
                            <div class="font-semibold text-slate-900 truncate">{{ $project->title }}</div>
                            <div class="text-sm text-slate-500 truncate max-w-xs">{{ $project->description }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex flex-wrap gap-1">
                        @foreach($project->technologies as $tech)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs rounded-full font-semibold" style="background-color: {{ $tech->color ?? '#e2e8f0' }}; color: {{ $tech->color ? '#fff' : '#475569' }}">
                                <i class="{{ $tech->display_icon }} text-[0.65rem]"></i> {{ $tech->name }}
                            </span>
                        @endforeach
                    </div>
                </td>
                <td class="px-6 py-4">
                    @if($project->is_visible)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Sim
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-500">
                            <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Não
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <form action="{{ route('admin.projects.toggle', $project->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full transition {{ $project->show_project_button ? 'bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                            <i class="fas fa-{{ $project->show_project_button ? 'eye' : 'eye-slash' }} text-[0.6rem]"></i>
                            {{ $project->show_project_button ? 'Ativo' : 'Inativo' }}
                        </button>
                    </form>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="inline-flex items-center gap-1">
                        <a href="{{ route('admin.projects.edit', $project->id) }}" class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 transition" title="Editar">
                            <i class="fas fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" class="inline" onsubmit="return confirm('Confirmar exclusão?')">
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
                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                    <i class="fas fa-diagram-project text-3xl mb-2 block"></i>
                    Nenhum projeto cadastrado.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Mobile Cards -->
<div class="lg:hidden space-y-3">
    @forelse($projects as $project)
    <div class="admin-card bg-white rounded-2xl p-4">
        <div class="flex items-start gap-3 mb-3">
            @if($project->thumbnail_path)
                <img src="{{ Storage::url($project->thumbnail_path) }}" class="h-12 w-16 object-cover rounded-lg border border-slate-200 shrink-0">
            @else
                <div class="h-12 w-16 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 shrink-0"><i class="fas fa-image"></i></div>
            @endif
            <div class="min-w-0 flex-1">
                <p class="font-semibold text-slate-900 truncate">{{ $project->title }}</p>
                <p class="text-sm text-slate-500 line-clamp-2">{{ $project->description }}</p>
            </div>
        </div>
        @if($project->technologies->count())
            <div class="flex flex-wrap gap-1 mb-3">
                @foreach($project->technologies as $tech)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs rounded-full font-semibold" style="background-color: {{ $tech->color ?? '#e2e8f0' }}; color: {{ $tech->color ? '#fff' : '#475569' }}">
                        <i class="{{ $tech->display_icon }} text-[0.6rem]"></i> {{ $tech->name }}
                    </span>
                @endforeach
            </div>
        @endif
        <div class="flex items-center gap-2 text-xs mb-3">
            @if($project->is_visible)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-semibold"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Visível</span>
            @else
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 font-semibold">Oculto</span>
            @endif
            <form action="{{ route('admin.projects.toggle', $project->id) }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full font-semibold {{ $project->show_project_button ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-500' }}">
                    <i class="fas fa-{{ $project->show_project_button ? 'eye' : 'eye-slash' }} text-[0.55rem]"></i> Botão {{ $project->show_project_button ? 'Ativo' : 'Inativo' }}
                </button>
            </form>
        </div>
        <div class="flex items-center gap-2 pt-3 border-t border-slate-100">
            <a href="{{ route('admin.projects.edit', $project->id) }}" class="flex-1 text-center py-2 rounded-lg text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 transition">Editar</a>
            <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Confirmar exclusão?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-2 rounded-lg text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">Excluir</button>
            </form>
        </div>
    </div>
    @empty
    <div class="admin-card bg-white rounded-2xl p-8 text-center text-slate-400">
        <i class="fas fa-diagram-project text-3xl mb-2 block"></i>
        Nenhum projeto cadastrado.
    </div>
    @endforelse
</div>
@endsection
