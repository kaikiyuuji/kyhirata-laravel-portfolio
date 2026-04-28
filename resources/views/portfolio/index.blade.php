@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <!-- Hero & Sobre Mim -->
    <header class="text-center md:text-left md:flex items-center gap-8 mb-16">
        @if(isset($aboutMe) && $aboutMe->avatar_path)
            <img src="{{ Storage::url($aboutMe->avatar_path) }}" alt="{{ $aboutMe->name }}" class="w-32 h-32 rounded-full mx-auto md:mx-0 object-cover border-4 border-white shadow-lg">
        @elseif(isset($aboutMe))
            <div class="w-32 h-32 rounded-full mx-auto md:mx-0 bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-4xl shadow-lg">
                {{ substr($aboutMe->name, 0, 1) }}
            </div>
        @endif
        
        @if(isset($aboutMe))
        <div>
            <h1 class="text-4xl font-extrabold text-gray-900 mb-2">{{ $aboutMe->name }}</h1>
            <h2 class="text-xl text-gray-600 mb-4">{{ $aboutMe->title }}</h2>
            
            @if($aboutMe->is_available_for_work)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span> Disponível para trabalho
                </span>
            @endif
        </div>
        @endif
    </header>

    @if(isset($aboutMe))
    <section class="mb-16">
        <h3 class="text-2xl font-bold mb-4 border-b pb-2">Sobre Mim</h3>
        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $aboutMe->bio }}</p>
        <div class="mt-4 flex gap-4 text-gray-600">
            @if($aboutMe->location) <span class="flex items-center gap-1"><i class="fas fa-map-marker-alt"></i> {{ $aboutMe->location }}</span> @endif
            @if($aboutMe->email) <a href="mailto:{{ $aboutMe->email }}" class="flex items-center gap-1 hover:text-blue-600"><i class="fas fa-envelope"></i> {{ $aboutMe->email }}</a> @endif
        </div>
    </section>
    @endif

    <!-- Experiências -->
    @if(isset($experiences) && $experiences->count() > 0)
    <section class="mb-16">
        <h3 class="text-2xl font-bold mb-6 border-b pb-2">Experiência Profissional</h3>
        <div class="space-y-8">
            @foreach($experiences as $experience)
                <div class="border-l-4 border-blue-500 pl-4">
                    <h4 class="text-lg font-bold">{{ $experience->role }} <span class="text-blue-600">@ {{ $experience->company }}</span></h4>
                    <p class="text-sm text-gray-500 mb-2">
                        {{ \Carbon\Carbon::parse($experience->started_at)->format('m/Y') }} - 
                        {{ $experience->ended_at ? \Carbon\Carbon::parse($experience->ended_at)->format('m/Y') : 'Atual' }}
                    </p>
                    <p class="text-gray-700">{{ $experience->description }}</p>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Projetos -->
    @if(isset($projects) && $projects->count() > 0)
    <section class="mb-16">
        <h3 class="text-2xl font-bold mb-6 border-b pb-2">Projetos</h3>
        <div class="grid md:grid-cols-2 gap-6">
            @foreach($projects as $project)
                <div class="bg-white rounded-lg shadow overflow-hidden border">
                    @if($project->thumbnail_path)
                        <img src="{{ Storage::url($project->thumbnail_path) }}" alt="{{ $project->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400"><i class="fas fa-image text-3xl"></i></div>
                    @endif
                    <div class="p-5">
                        <h4 class="text-xl font-bold mb-2">{{ $project->title }}</h4>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $project->description }}</p>
                        
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($project->technologies as $tech)
                                <span class="px-2 py-1 text-xs font-semibold rounded" style="background-color: {{ $tech->color ?? '#e2e8f0' }}; color: {{ $tech->color ? '#fff' : '#475569' }}">
                                    {{ $tech->name }}
                                </span>
                            @endforeach
                        </div>

                        <div class="flex gap-3 items-center">
                            @if($project->github_url)
                                <a href="{{ $project->github_url }}" target="_blank" class="text-gray-700 hover:text-black"><i class="fab fa-github text-2xl"></i></a>
                            @endif
                            @if($project->demo_url && $project->show_project_button)
                                <a href="{{ $project->demo_url }}" target="_blank" class="text-sm bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 ml-auto font-medium transition-colors">Ver Projeto</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Contato -->
    @if(isset($socialLinks) && $socialLinks->count() > 0)
    <section class="text-center">
        <h3 class="text-2xl font-bold mb-6 border-b pb-2">Redes Sociais</h3>
        <div class="flex justify-center gap-6 mt-6">
            @foreach($socialLinks as $link)
                <a href="{{ $link->url }}" target="_blank" class="text-gray-500 hover:text-blue-600 text-4xl transition-colors" title="{{ $link->platform }}">
                    <i class="{{ $link->icon }}"></i>
                </a>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
