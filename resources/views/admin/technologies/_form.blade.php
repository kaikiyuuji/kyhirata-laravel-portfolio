<div class="grid grid-cols-1 gap-5">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
        <input type="text" name="name" value="{{ old('name', $technology->name ?? '') }}" class="w-full border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">
        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Classe do Ícone <span class="text-gray-400 text-xs">(opcional, ex: devicon-laravel-plain)</span></label>
        <div class="flex gap-2">
            <input type="text" name="icon" value="{{ old('icon', $technology->icon ?? '') }}" 
                placeholder="Ex: devicon-laravel-plain colored"
                class="flex-1 border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500 font-mono">
            <a href="https://devicon.dev/" target="_blank" class="bg-gray-100 border border-gray-300 px-3 py-2 rounded text-sm text-gray-600 hover:bg-gray-200" title="Procurar ícones">
                <i class="fas fa-search"></i>
            </a>
        </div>
        <p class="text-xs text-gray-500 mt-1">Deixe em branco para usar o ícone padrão baseado no nome.</p>
        @error('icon') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Cor <span class="text-gray-400 text-xs">(hexadecimal, ex: #3B82F6)</span></label>
        <div class="flex gap-3 items-center">
            <input type="color" name="color_picker" value="{{ old('color', $technology->color ?? '#3B82F6') }}"
                class="h-10 w-14 cursor-pointer rounded border border-gray-300"
                oninput="document.getElementById('color').value = this.value">
            <input type="text" id="color" name="color" value="{{ old('color', $technology->color ?? '') }}"
                placeholder="#3B82F6"
                class="flex-1 border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500 font-mono"
                oninput="document.querySelector('[name=color_picker]').value = this.value">
        </div>
        @error('color') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
</div>
