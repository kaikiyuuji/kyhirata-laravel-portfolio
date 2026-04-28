@extends('layouts.admin')

@section('title', 'Editar Experiência')

@section('content')
<div class="max-w-2xl bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.experiences.update', $experience->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.experiences._form', compact('experience'))
        <div class="flex justify-end gap-3 border-t pt-4 mt-6">
            <a href="{{ route('admin.experiences.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow transition">Atualizar</button>
        </div>
    </form>
</div>
@endsection
