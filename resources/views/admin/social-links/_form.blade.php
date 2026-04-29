<div class="grid grid-cols-1 gap-5">
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Plataforma</label>
        <input type="text" name="platform" value="{{ old('platform', $socialLink->platform ?? '') }}" placeholder="ex: GitHub, LinkedIn" class="w-full border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm transition">
        @error('platform') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">URL</label>
        <input type="url" name="url" value="{{ old('url', $socialLink->url ?? '') }}" placeholder="https://..." class="w-full border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm transition">
        @error('url') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
            Ícone <span class="text-slate-400 text-xs font-normal">(classe Devicon, ex: <code class="bg-slate-100 px-1 rounded">devicon-github-original</code>)</span>
        </label>
        <div class="flex gap-2 items-center">
            <input type="text" name="icon" id="icon_input" value="{{ old('icon', $socialLink->icon ?? '') }}" placeholder="devicon-github-original" class="flex-1 border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm font-mono transition">
            <div class="h-11 w-11 rounded-xl bg-slate-100 flex items-center justify-center text-2xl text-slate-700 shrink-0 border border-slate-200">
                <i id="icon_preview" class="{{ $socialLink->display_icon ?? '' }}"></i>
            </div>
            <a href="https://devicon.dev/" target="_blank" class="inline-flex items-center gap-2 bg-slate-100 border border-slate-200 px-4 py-2.5 rounded-xl text-sm text-slate-600 hover:bg-slate-200 transition shrink-0" title="Procurar ícones">
                <i class="fas fa-search"></i>
            </a>
        </div>
        <script>
            document.getElementById('icon_input').addEventListener('input', function() {
                document.getElementById('icon_preview').className = this.value;
            });
        </script>
        <p class="text-xs text-slate-400 mt-1.5">Deixe em branco para usar o ícone padrão baseado na plataforma.</p>
        @error('icon') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Ordem</label>
        <input type="number" name="order" value="{{ old('order', $socialLink->order ?? 0) }}" min="0" class="w-full border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm transition">
        @error('order') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>
    <div class="flex items-center gap-3">
        <input type="hidden" name="is_visible" value="0">
        <input type="checkbox" name="is_visible" id="is_visible" value="1" {{ old('is_visible', $socialLink->is_visible ?? true) ? 'checked' : '' }} class="h-5 w-5 text-blue-600 focus:ring-blue-500/40 border-slate-300 rounded-md transition">
        <label for="is_visible" class="text-sm font-medium text-slate-700">Visível no Portfólio</label>
        @error('is_visible') <span class="text-red-500 text-xs ml-2">{{ $message }}</span> @enderror
    </div>
</div>
