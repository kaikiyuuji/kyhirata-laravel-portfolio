<div class="grid grid-cols-1 gap-5">
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Título</label>
        <input type="text" name="title" value="{{ old('title', $project->title ?? '') }}" class="w-full border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm transition">
        @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Descrição</label>
        <textarea name="description" rows="4" class="w-full border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm transition">{{ old('description', $project->description ?? '') }}</textarea>
        @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">URL GitHub</label>
            <input type="url" name="github_url" value="{{ old('github_url', $project->github_url ?? '') }}" placeholder="https://github.com/..." class="w-full border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm transition">
            @error('github_url') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">URL Demo</label>
            <input type="url" name="demo_url" value="{{ old('demo_url', $project->demo_url ?? '') }}" class="w-full border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm transition">
            @error('demo_url') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tecnologias</label>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-1 p-3 border border-slate-200 rounded-xl bg-slate-50/50">
            @foreach($technologies as $tech)
                <label class="flex items-center gap-2.5 text-sm px-2 py-1.5 rounded-lg hover:bg-white transition cursor-pointer">
                    <input type="checkbox" name="technology_ids[]" value="{{ $tech->id }}"
                        {{ in_array($tech->id, old('technology_ids', isset($project) ? $project->technologies->pluck('id')->toArray() : [])) ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 border-slate-300 rounded transition">
                    <span class="flex items-center gap-1.5">
                        <i class="{{ $tech->display_icon }} text-xs" style="color: {{ $tech->color ?? '#64748b' }}"></i>
                        {{ $tech->name }}
                    </span>
                </label>
            @endforeach
        </div>
        @error('technology_ids') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Thumbnail</label>
        <input type="file" name="thumbnail" accept="image/*" class="w-full border border-slate-200 rounded-xl shadow-sm p-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-1.5 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 transition">
        @error('thumbnail') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        @if(isset($project) && $project->thumbnail_path)
            <div class="mt-3 flex items-center gap-3">
                <img src="{{ Storage::url($project->thumbnail_path) }}" class="w-20 h-14 object-cover rounded-xl border border-slate-200 shadow-sm">
                <span class="text-sm text-slate-500">Thumbnail atual</span>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Ordem</label>
            <input type="number" name="order" value="{{ old('order', $project->order ?? 0) }}" min="0" class="w-full border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm transition">
            @error('order') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div class="flex items-center gap-3 mt-5">
            <input type="hidden" name="is_visible" value="0">
            <input type="checkbox" id="is_visible" name="is_visible" value="1" {{ old('is_visible', $project->is_visible ?? true) ? 'checked' : '' }} class="h-5 w-5 text-blue-600 focus:ring-blue-500/40 border-slate-300 rounded-md transition">
            <label for="is_visible" class="text-sm font-medium text-slate-700">Visível no portfólio</label>
            @error('is_visible') <span class="text-red-500 text-xs ml-2">{{ $message }}</span> @enderror
        </div>
        <div class="flex items-center gap-3 mt-5">
            <input type="hidden" name="show_project_button" value="0">
            <input type="checkbox" id="show_project_button" name="show_project_button" value="1" {{ old('show_project_button', $project->show_project_button ?? false) ? 'checked' : '' }} class="h-5 w-5 text-blue-600 focus:ring-blue-500/40 border-slate-300 rounded-md transition">
            <label for="show_project_button" class="text-sm font-medium text-slate-700">Exibir botão "Ver Projeto"</label>
            @error('show_project_button') <span class="text-red-500 text-xs ml-2">{{ $message }}</span> @enderror
        </div>
    </div>
</div>
