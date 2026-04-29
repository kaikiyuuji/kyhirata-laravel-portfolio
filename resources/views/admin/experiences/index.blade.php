@extends('layouts.admin')

@section('title', 'Experiências')

@section('content')
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
    <div>
        <h3 class="text-lg font-bold text-slate-800">Todas as Experiências</h3>
        <p class="text-sm text-slate-500 mt-0.5">Gerencie sua trajetória profissional</p>
    </div>
    <a href="{{ route('admin.experiences.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl shadow-sm text-sm font-semibold transition hover:-translate-y-0.5">
        <i class="fas fa-plus text-xs"></i> Nova Experiência
    </a>
</div>

<!-- Desktop Table -->
<div class="hidden md:block admin-card bg-white rounded-2xl overflow-hidden">
    <table class="min-w-full">
        <thead>
            <tr class="border-b border-slate-100">
                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Empresa / Cargo</th>
                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Período</th>
                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Visível</th>
                <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($experiences as $experience)
            <tr class="hover:bg-slate-50/60 transition-colors">
                <td class="px-6 py-4">
                    <div class="font-semibold text-slate-900">{{ $experience->company }}</div>
                    <div class="text-sm text-slate-500">{{ $experience->role }}</div>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">
                    {{ \Carbon\Carbon::parse($experience->started_at)->format('m/Y') }} —
                    {{ $experience->ended_at ? \Carbon\Carbon::parse($experience->ended_at)->format('m/Y') : 'Atual' }}
                </td>
                <td class="px-6 py-4">
                    @if($experience->is_visible)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Sim
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-500">
                            <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Não
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="inline-flex items-center gap-1">
                        <a href="{{ route('admin.experiences.edit', $experience->id) }}" class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 transition" title="Editar">
                            <i class="fas fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.experiences.destroy', $experience->id) }}" method="POST" class="inline" onsubmit="return confirm('Confirmar exclusão?')">
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
                    <i class="fas fa-briefcase text-3xl mb-2 block"></i>
                    Nenhuma experiência cadastrada.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Mobile Cards -->
<div class="md:hidden space-y-3">
    @forelse($experiences as $experience)
    <div class="admin-card bg-white rounded-2xl p-4">
        <div class="flex items-start justify-between gap-3 mb-3">
            <div>
                <p class="font-semibold text-slate-900">{{ $experience->company }}</p>
                <p class="text-sm text-slate-500">{{ $experience->role }}</p>
            </div>
            @if($experience->is_visible)
                <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Sim
                </span>
            @else
                <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded-full bg-slate-100 text-slate-500">Não</span>
            @endif
        </div>
        <p class="text-xs text-slate-400 mb-3">
            <i class="far fa-calendar mr-1"></i>
            {{ \Carbon\Carbon::parse($experience->started_at)->format('m/Y') }} — {{ $experience->ended_at ? \Carbon\Carbon::parse($experience->ended_at)->format('m/Y') : 'Atual' }}
        </p>
        <div class="flex items-center gap-2 pt-3 border-t border-slate-100">
            <a href="{{ route('admin.experiences.edit', $experience->id) }}" class="flex-1 text-center py-2 rounded-lg text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 transition">Editar</a>
            <form action="{{ route('admin.experiences.destroy', $experience->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Confirmar exclusão?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-2 rounded-lg text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">Excluir</button>
            </form>
        </div>
    </div>
    @empty
    <div class="admin-card bg-white rounded-2xl p-8 text-center text-slate-400">
        <i class="fas fa-briefcase text-3xl mb-2 block"></i>
        Nenhuma experiência cadastrada.
    </div>
    @endforelse
</div>
@endsection
