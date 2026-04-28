@extends('layouts.admin')

@section('title', 'Experiências')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-semibold text-gray-700">Todas as Experiências</h3>
    <a href="{{ route('admin.experiences.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-sm font-medium transition">
        + Nova Experiência
    </a>
</div>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa / Cargo</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Período</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visível</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($experiences as $experience)
            <tr>
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-900">{{ $experience->company }}</div>
                    <div class="text-sm text-gray-500">{{ $experience->role }}</div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    {{ \Carbon\Carbon::parse($experience->started_at)->format('m/Y') }} —
                    {{ $experience->ended_at ? \Carbon\Carbon::parse($experience->ended_at)->format('m/Y') : 'Atual' }}
                </td>
                <td class="px-6 py-4">
                    @if($experience->is_visible)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Sim</span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Não</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right space-x-2">
                    <a href="{{ route('admin.experiences.edit', $experience->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Editar</a>
                    <form action="{{ route('admin.experiences.destroy', $experience->id) }}" method="POST" class="inline" onsubmit="return confirm('Confirmar exclusão?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Excluir</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-10 text-center text-gray-400">Nenhuma experiência cadastrada.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
