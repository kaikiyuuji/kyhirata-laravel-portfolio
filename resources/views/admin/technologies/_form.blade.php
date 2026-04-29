<div class="grid grid-cols-1 gap-5">
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nome</label>
        <input type="text" name="name" value="{{ old('name', $technology->name ?? '') }}" class="w-full border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm transition">
        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
            Classe do Ícone <span class="text-slate-400 text-xs font-normal">(opcional, ex: devicon-laravel-plain)</span>
        </label>
        <div class="flex gap-2">
            <input type="text" name="icon" value="{{ old('icon', $technology->icon ?? '') }}"
                placeholder="Ex: devicon-laravel-plain colored"
                class="flex-1 border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm font-mono transition">
            <a href="https://devicon.dev/" target="_blank" class="inline-flex items-center gap-2 bg-slate-100 border border-slate-200 px-4 py-2.5 rounded-xl text-sm text-slate-600 hover:bg-slate-200 transition shrink-0" title="Procurar ícones">
                <i class="fas fa-search"></i>
            </a>
        </div>
        <p class="text-xs text-slate-400 mt-1.5">Deixe em branco para usar o ícone padrão baseado no nome.</p>
        @error('icon') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
            Cor <span class="text-slate-400 text-xs font-normal">(hexadecimal, ex: #3B82F6)</span>
        </label>
        <div class="flex gap-3 items-center">
            <input type="color" name="color_picker" value="{{ old('color', $technology->color ?? '#3B82F6') }}"
                class="h-11 w-14 cursor-pointer rounded-xl border border-slate-200 shadow-sm"
                oninput="document.getElementById('color').value = this.value">
            <input type="text" id="color" name="color" value="{{ old('color', $technology->color ?? '') }}"
                placeholder="#3B82F6"
                class="flex-1 border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm font-mono transition"
                oninput="document.querySelector('[name=color_picker]').value = this.value">
        </div>
        @error('color') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>
</div>
