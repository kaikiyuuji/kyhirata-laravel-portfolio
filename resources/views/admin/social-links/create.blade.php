@extends('layouts.admin')

@section('title', 'Novo Link Social')

@section('content')
<div class="max-w-lg bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.social-links.store') }}" method="POST">
        @csrf
        @include('admin.social-links._form', ['socialLink' => null])
        <div class="flex justify-end gap-3 border-t pt-4 mt-6">
            <a href="{{ route('admin.social-links.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow transition">Salvar</button>
        </div>
    </form>
</div>
@endsection
