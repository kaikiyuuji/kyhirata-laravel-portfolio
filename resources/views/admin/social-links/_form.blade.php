<div class="grid grid-cols-1 gap-5">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Plataforma</label>
        <input type="text" name="platform" value="{{ old('platform', $socialLink->platform ?? '') }}" placeholder="ex: GitHub, LinkedIn" class="w-full border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">
        @error('platform') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
        <input type="url" name="url" value="{{ old('url', $socialLink->url ?? '') }}" placeholder="https://..." class="w-full border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">
        @error('url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Ícone <span class="text-gray-400 text-xs">(classe Font Awesome, ex: <code>fab fa-github</code>)</span>
        </label>
        <div class="flex gap-3 items-center">
            <input type="text" name="icon" id="icon_input" value="{{ old('icon', $socialLink->icon ?? '') }}" placeholder="fab fa-github" class="flex-1 border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500 font-mono text-sm">
            <span class="text-2xl text-gray-500 w-8 text-center"><i id="icon_preview" class="{{ old('icon', $socialLink->icon ?? '') }}"></i></span>
        </div>
        <script>
            document.getElementById('icon_input').addEventListener('input', function() {
                document.getElementById('icon_preview').className = this.value;
            });
        </script>
        @error('icon') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Ordem</label>
        <input type="number" name="order" value="{{ old('order', $socialLink->order ?? 0) }}" min="0" class="w-full border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">
        @error('order') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
    <div class="flex items-center gap-2">
        <input type="hidden" name="is_visible" value="0">
        <input type="checkbox" name="is_visible" id="is_visible" value="1" {{ old('is_visible', $socialLink->is_visible ?? true) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
        <label for="is_visible" class="text-sm font-medium text-gray-700">Visível no Portfólio</label>
        @error('is_visible') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
</div>
