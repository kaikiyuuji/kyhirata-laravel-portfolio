@extends('layouts.admin')

@section('title', 'Links Sociais')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-semibold text-gray-700">Todos os Links Sociais</h3>
    <a href="{{ route('admin.social-links.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-sm font-medium transition">
        + Novo Link
    </a>
</div>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plataforma</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ícone</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($socialLinks as $link)
            <tr>
                <td class="px-6 py-4 font-medium text-gray-900">{{ $link->platform }}</td>
                <td class="px-6 py-4">
                    <a href="{{ $link->url }}" target="_blank" class="text-blue-600 hover:underline text-sm truncate max-w-xs block">{{ $link->url }}</a>
                </td>
                <td class="px-6 py-4">
                    <span class="font-mono text-sm text-gray-500">{{ $link->icon }}</span>
                    @if($link->icon) <i class="{{ $link->icon }} ml-2 text-gray-600"></i> @endif
                </td>
                <td class="px-6 py-4 text-right space-x-2">
                    <a href="{{ route('admin.social-links.edit', $link->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Editar</a>
                    <form action="{{ route('admin.social-links.destroy', $link->id) }}" method="POST" class="inline" onsubmit="return confirm('Confirmar exclusão?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Excluir</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-10 text-center text-gray-400">Nenhum link social cadastrado.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
