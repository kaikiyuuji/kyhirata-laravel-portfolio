@extends('layouts.admin')

@section('title', 'Visão Geral do Sistema')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white rounded-lg shadow p-6 flex items-center">
        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
            <i class="fas fa-briefcase text-2xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium">Experiências</p>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['experiences_count'] ?? 0 }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 flex items-center">
        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
            <i class="fas fa-project-diagram text-2xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium">Projetos</p>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['projects_count'] ?? 0 }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 flex items-center">
        <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
            <i class="fas fa-code text-2xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium">Tecnologias</p>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['technologies_count'] ?? 0 }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 flex items-center">
        <div class="p-3 rounded-full bg-pink-100 text-pink-600 mr-4">
            <i class="fas fa-link text-2xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium">Links Sociais</p>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['social_links_count'] ?? 0 }}</p>
        </div>
    </div>
</div>

<div class="mt-8 bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-bold mb-4">Ações Rápidas</h3>
    <div class="flex flex-wrap gap-4">
        <a href="{{ route('admin.about.edit') }}" class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700 transition">Atualizar Sobre Mim</a>
        <a href="{{ route('admin.projects.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Adicionar Projeto</a>
        <a href="{{ route('portfolio.index') }}" target="_blank" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition"><i class="fas fa-external-link-alt"></i> Ver Portfólio Público</a>
    </div>
</div>
@endsection
