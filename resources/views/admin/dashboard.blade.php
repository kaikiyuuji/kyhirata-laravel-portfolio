@extends('layouts.admin')

@section('title', 'Visão Geral')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-8">
    <div class="admin-card bg-white rounded-2xl p-5 sm:p-6">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i class="fas fa-briefcase text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Experiências</p>
                <p class="text-2xl sm:text-3xl font-bold text-slate-900 mt-0.5">{{ $stats['experiences_count'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="admin-card bg-white rounded-2xl p-5 sm:p-6">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <i class="fas fa-diagram-project text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Projetos</p>
                <p class="text-2xl sm:text-3xl font-bold text-slate-900 mt-0.5">{{ $stats['projects_count'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="admin-card bg-white rounded-2xl p-5 sm:p-6">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center shrink-0">
                <i class="fas fa-code text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Tecnologias</p>
                <p class="text-2xl sm:text-3xl font-bold text-slate-900 mt-0.5">{{ $stats['technologies_count'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="admin-card bg-white rounded-2xl p-5 sm:p-6">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center shrink-0">
                <i class="fas fa-share-nodes text-xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Redes Sociais</p>
                <p class="text-2xl sm:text-3xl font-bold text-slate-900 mt-0.5">{{ $stats['social_links_count'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="admin-card bg-white rounded-2xl p-5 sm:p-6">
    <h3 class="text-base font-bold text-slate-800 mb-4">
        <i class="fas fa-bolt text-amber-500 mr-2"></i>Ações Rápidas
    </h3>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <a href="{{ route('admin.about.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-900 text-white text-sm font-medium hover:-translate-y-0.5 hover:shadow-lg transition-all">
            <i class="fas fa-user-pen"></i> Atualizar Sobre Mim
        </a>
        <a href="{{ route('admin.projects.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white text-sm font-medium hover:-translate-y-0.5 hover:shadow-lg transition-all">
            <i class="fas fa-plus"></i> Adicionar Projeto
        </a>
        <a href="{{ route('portfolio.index') }}" target="_blank" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-100 text-slate-700 text-sm font-medium hover:bg-slate-200 hover:-translate-y-0.5 transition-all">
            <i class="fas fa-arrow-up-right-from-square"></i> Ver Portfólio Público
        </a>
    </div>
</div>
@endsection
