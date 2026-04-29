@extends('layouts.admin')

@section('title', 'Editar Link Social')

@section('content')
<div class="max-w-lg">
    <div class="admin-card bg-white rounded-2xl p-5 sm:p-7">
        <form action="{{ route('admin.social-links.update', $socialLink->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.social-links._form', compact('socialLink'))
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 border-t border-slate-100 pt-5 mt-6">
                <a href="{{ route('admin.social-links.index') }}" class="px-5 py-2.5 text-sm text-slate-600 hover:text-slate-900 text-center rounded-xl hover:bg-slate-100 transition">Cancelar</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-xl shadow-sm transition hover:-translate-y-0.5">Atualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection
