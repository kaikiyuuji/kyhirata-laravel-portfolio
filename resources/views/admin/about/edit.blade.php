@extends('layouts.admin')

@section('title', 'Editar Sobre Mim')

@section('content')
<div class="max-w-4xl">
    <div class="admin-card bg-white rounded-2xl p-5 sm:p-7">
        <form action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nome Completo</label>
                    <input type="text" name="name" value="{{ old('name', $aboutMe->name ?? '') }}" class="w-full border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm transition">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Título Profissional</label>
                    <input type="text" name="title" value="{{ old('title', $aboutMe->title ?? '') }}" class="w-full border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm transition">
                    @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Biografia</label>
                    <textarea name="bio" rows="5" class="w-full border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm transition">{{ old('bio', $aboutMe->bio ?? '') }}</textarea>
                    @error('bio') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Localização</label>
                    <input type="text" name="location" value="{{ old('location', $aboutMe->location ?? '') }}" class="w-full border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm transition">
                    @error('location') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">E-mail Público</label>
                    <input type="email" name="email" value="{{ old('email', $aboutMe->email ?? '') }}" class="w-full border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm transition">
                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Avatar (Imagem)</label>
                    <input type="file" name="avatar" accept="image/*" class="w-full border border-slate-200 rounded-xl shadow-sm p-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-1.5 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 transition">
                    @error('avatar') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    @if(isset($aboutMe) && $aboutMe->avatar_path)
                        <div class="mt-3 flex items-center gap-3">
                            <img src="{{ Storage::url($aboutMe->avatar_path) }}" class="w-14 h-14 rounded-xl object-cover border border-slate-200 shadow-sm">
                            <span class="text-sm text-slate-500">Avatar atual</span>
                        </div>
                    @endif
                </div>

                <div class="md:col-span-2 flex items-center gap-3 mt-2">
                    <input type="hidden" name="is_available_for_work" value="0">
                    <input type="checkbox" name="is_available_for_work" value="1" id="is_available_for_work" {{ old('is_available_for_work', $aboutMe->is_available_for_work ?? false) ? 'checked' : '' }} class="h-5 w-5 text-blue-600 focus:ring-blue-500/40 border-slate-300 rounded-md transition">
                    <label for="is_available_for_work" class="text-sm font-medium text-slate-700">
                        Disponível para contratação / trabalho
                    </label>
                    @error('is_available_for_work') <span class="text-red-500 text-xs ml-2">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 border-t border-slate-100 pt-5">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-xl shadow-sm transition hover:-translate-y-0.5">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
