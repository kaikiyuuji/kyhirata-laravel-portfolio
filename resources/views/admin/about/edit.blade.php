@extends('layouts.admin')

@section('title', 'Editar Sobre Mim')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-4xl">
    <form action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome Completo</label>
                <input type="text" name="name" value="{{ old('name', $aboutMe->name ?? '') }}" class="w-full border-gray-300 rounded shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título Profissional</label>
                <input type="text" name="title" value="{{ old('title', $aboutMe->title ?? '') }}" class="w-full border-gray-300 rounded shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Biografia</label>
                <textarea name="bio" rows="5" class="w-full border-gray-300 rounded shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">{{ old('bio', $aboutMe->bio ?? '') }}</textarea>
                @error('bio') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Localização</label>
                <input type="text" name="location" value="{{ old('location', $aboutMe->location ?? '') }}" class="w-full border-gray-300 rounded shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                @error('location') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">E-mail Público</label>
                <input type="email" name="email" value="{{ old('email', $aboutMe->email ?? '') }}" class="w-full border-gray-300 rounded shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Avatar (Imagem)</label>
                <input type="file" name="avatar" accept="image/*" class="w-full border-gray-300 rounded shadow-sm p-1 border">
                @error('avatar') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @if(isset($aboutMe) && $aboutMe->avatar_path)
                    <div class="mt-2 text-sm text-gray-500">
                        <img src="{{ Storage::url($aboutMe->avatar_path) }}" class="w-16 h-16 rounded object-cover inline border"> Avatar atual
                    </div>
                @endif
            </div>

            <div class="flex items-center mt-6">
                <input type="hidden" name="is_available_for_work" value="0">
                <input type="checkbox" name="is_available_for_work" value="1" id="is_available_for_work" {{ old('is_available_for_work', $aboutMe->is_available_for_work ?? false) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="is_available_for_work" class="ml-2 block text-sm text-gray-900">
                    Disponível para contratação / trabalho
                </label>
                @error('is_available_for_work') <span class="text-red-500 text-sm ml-2">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex justify-end border-t pt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow transition">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>
@endsection
