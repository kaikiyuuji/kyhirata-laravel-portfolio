@extends('layouts.admin')

@section('title', 'Tecnologias')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-semibold text-gray-700">Todas as Tecnologias</h3>
    <a href="{{ route('admin.technologies.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-sm font-medium transition">
        + Nova Tecnologia
    </a>
</div>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ícone</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cor</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($technologies as $technology)
            <tr>
                <td class="px-6 py-4">
                    <div class="text-2xl text-gray-600">
                        <i class="{{ $technology->display_icon }}"></i>
                    </div>
                </td>
                <td class="px-6 py-4 font-medium text-gray-900">{{ $technology->name }}</td>
                <td class="px-6 py-4">
                    @if($technology->color)
                        <span class="inline-flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full border" style="background-color: {{ $technology->color }}"></span>
                            <span class="text-sm text-gray-500 font-mono">{{ $technology->color }}</span>
                        </span>
                    @else
                        <span class="text-gray-400 text-sm">—</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right space-x-2">
                    <a href="{{ route('admin.technologies.edit', $technology->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Editar</a>
                    <form action="{{ route('admin.technologies.destroy', $technology->id) }}" method="POST" class="inline" onsubmit="return confirm('Confirmar exclusão?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Excluir</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-10 text-center text-gray-400">Nenhuma tecnologia cadastrada.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
