<div class="grid grid-cols-1 gap-5">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
        <input type="text" name="title" value="{{ old('title', $project->title ?? '') }}" class="w-full border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">
        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
        <textarea name="description" rows="4" class="w-full border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $project->description ?? '') }}</textarea>
        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">URL GitHub</label>
            <input type="url" name="github_url" value="{{ old('github_url', $project->github_url ?? '') }}" placeholder="https://github.com/..." class="w-full border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">
            @error('github_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">URL Demo</label>
            <input type="url" name="demo_url" value="{{ old('demo_url', $project->demo_url ?? '') }}" class="w-full border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">
            @error('demo_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tecnologias</label>
        <div class="grid grid-cols-3 gap-2 mt-1">
            @foreach($technologies as $tech)
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="technology_ids[]" value="{{ $tech->id }}"
                        {{ in_array($tech->id, old('technology_ids', isset($project) ? $project->technologies->pluck('id')->toArray() : [])) ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                    <span>{{ $tech->name }}</span>
                </label>
            @endforeach
        </div>
        @error('technology_ids') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail</label>
        <input type="file" name="thumbnail" accept="image/*" class="w-full border border-gray-300 rounded p-1">
        @error('thumbnail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        @if(isset($project) && $project->thumbnail_path)
            <div class="mt-2 text-sm text-gray-500">
                <img src="{{ Storage::url($project->thumbnail_path) }}" class="w-20 h-14 object-cover rounded inline border"> Thumbnail atual
            </div>
        @endif
    </div>

    <div class="grid grid-cols-3 gap-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ordem</label>
            <input type="number" name="order" value="{{ old('order', $project->order ?? 0) }}" min="0" class="w-full border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">
            @error('order') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="flex items-center mt-5">
            <input type="hidden" name="is_visible" value="0">
            <input type="checkbox" id="is_visible" name="is_visible" value="1" {{ old('is_visible', $project->is_visible ?? true) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded">
            <label for="is_visible" class="ml-2 text-sm text-gray-700">Visível no portfólio</label>
            @error('is_visible') <span class="text-red-500 text-sm ml-2">{{ $message }}</span> @enderror
        </div>
        <div class="flex items-center mt-5">
            <input type="hidden" name="show_project_button" value="0">
            <input type="checkbox" id="show_project_button" name="show_project_button" value="1" {{ old('show_project_button', $project->show_project_button ?? false) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded">
            <label for="show_project_button" class="ml-2 text-sm text-gray-700">Exibir botão "Ver Projeto"</label>
            @error('show_project_button') <span class="text-red-500 text-sm ml-2">{{ $message }}</span> @enderror
        </div>
    </div>
</div>
