@extends('layouts.admin')

@section('title', 'Projetos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-semibold text-gray-700">Todos os Projetos</h3>
    <a href="{{ route('admin.projects.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-sm font-medium transition">
        + Novo Projeto
    </a>
</div>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Projeto</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tecnologias</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visível</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Botão</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($projects as $project)
            <tr>
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-900">{{ $project->title }}</div>
                    <div class="text-sm text-gray-500 truncate max-w-xs">{{ $project->description }}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex flex-wrap gap-1">
                        @foreach($project->technologies as $tech)
                            <span class="px-2 py-0.5 text-xs rounded font-semibold" style="background-color: {{ $tech->color ?? '#e2e8f0' }}; color: {{ $tech->color ? '#fff' : '#475569' }}">{{ $tech->name }}</span>
                        @endforeach
                    </div>
                </td>
                <td class="px-6 py-4">
                    @if($project->is_visible)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Sim</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Não</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <form action="{{ route('admin.projects.toggle', $project->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-2 py-1 text-xs font-semibold rounded-full transition {{ $project->show_project_button ? 'bg-blue-100 text-blue-800 hover:bg-blue-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            {{ $project->show_project_button ? 'Ativo' : 'Inativo' }}
                        </button>
                    </form>
                </td>
                <td class="px-6 py-4 text-right space-x-2">
                    <a href="{{ route('admin.projects.edit', $project->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Editar</a>
                    <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" class="inline" onsubmit="return confirm('Confirmar exclusão?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Excluir</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-10 text-center text-gray-400">Nenhum projeto cadastrado.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
