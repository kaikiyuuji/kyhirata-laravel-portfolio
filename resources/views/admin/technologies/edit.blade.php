@extends('layouts.admin')

@section('title', 'Editar Tecnologia')

@section('content')
<div class="max-w-lg bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.technologies.update', $technology->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.technologies._form', compact('technology'))
        <div class="flex justify-end gap-3 border-t pt-4 mt-6">
            <a href="{{ route('admin.technologies.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow transition">Atualizar</button>
        </div>
    </form>
</div>
@endsection
