@extends('layouts.admin')

@section('title', 'Tecnologias')

@section('content')
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
    <div>
        <h3 class="text-lg font-bold text-slate-800">Todas as Tecnologias</h3>
        <p class="text-sm text-slate-500 mt-0.5">Gerencie as tecnologias do seu portfólio</p>
    </div>
    <a href="{{ route('admin.technologies.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl shadow-sm text-sm font-semibold transition hover:-translate-y-0.5">
        <i class="fas fa-plus text-xs"></i> Nova Tecnologia
    </a>
</div>

<!-- Desktop Table -->
<div class="hidden md:block admin-card bg-white rounded-2xl overflow-hidden">
    <table class="min-w-full">
        <thead>
            <tr class="border-b border-slate-100">
                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Ícone</th>
                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nome</th>
                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Cor</th>
                <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($technologies as $technology)
            <tr class="hover:bg-slate-50/60 transition-colors">
                <td class="px-6 py-4">
                    <div class="h-10 w-10 rounded-xl flex items-center justify-center text-xl" style="background-color: {{ ($technology->color ?? '#e2e8f0') . '20' }}; color: {{ $technology->color ?? '#64748b' }};">
                        <i class="{{ $technology->display_icon }}"></i>
                    </div>
                </td>
                <td class="px-6 py-4 font-semibold text-slate-900">{{ $technology->name }}</td>
                <td class="px-6 py-4">
                    @if($technology->color)
                        <span class="inline-flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full border border-slate-200 shadow-sm" style="background-color: {{ $technology->color }}"></span>
                            <span class="text-sm text-slate-500 font-mono">{{ $technology->color }}</span>
                        </span>
                    @else
                        <span class="text-slate-400 text-sm">—</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="inline-flex items-center gap-1">
                        <a href="{{ route('admin.technologies.edit', $technology->id) }}" class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 transition" title="Editar">
                            <i class="fas fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.technologies.destroy', $technology->id) }}" method="POST" class="inline" onsubmit="return confirm('Confirmar exclusão?')">
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
                    <i class="fas fa-code text-3xl mb-2 block"></i>
                    Nenhuma tecnologia cadastrada.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Mobile Cards -->
<div class="md:hidden space-y-3">
    @forelse($technologies as $technology)
    <div class="admin-card bg-white rounded-2xl p-4">
        <div class="flex items-center gap-3 mb-3">
            <div class="h-12 w-12 rounded-xl flex items-center justify-center text-2xl shrink-0" style="background-color: {{ ($technology->color ?? '#e2e8f0') . '20' }}; color: {{ $technology->color ?? '#64748b' }};">
                <i class="{{ $technology->display_icon }}"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="font-semibold text-slate-900">{{ $technology->name }}</p>
                @if($technology->color)
                    <div class="flex items-center gap-1.5 mt-1">
                        <span class="w-3 h-3 rounded-full border" style="background-color: {{ $technology->color }}"></span>
                        <span class="text-xs text-slate-500 font-mono">{{ $technology->color }}</span>
                    </div>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-2 pt-3 border-t border-slate-100">
            <a href="{{ route('admin.technologies.edit', $technology->id) }}" class="flex-1 text-center py-2 rounded-lg text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 transition">Editar</a>
            <form action="{{ route('admin.technologies.destroy', $technology->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Confirmar exclusão?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-2 rounded-lg text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">Excluir</button>
            </form>
        </div>
    </div>
    @empty
    <div class="admin-card bg-white rounded-2xl p-8 text-center text-slate-400">
        <i class="fas fa-code text-3xl mb-2 block"></i>
        Nenhuma tecnologia cadastrada.
    </div>
    @endforelse
</div>
@endsection
