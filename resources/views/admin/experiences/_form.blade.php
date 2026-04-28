<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Empresa</label>
        <input type="text" name="company" value="{{ old('company', $experience->company ?? '') }}" class="w-full border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">
        @error('company') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Cargo</label>
        <input type="text" name="role" value="{{ old('role', $experience->role ?? '') }}" class="w-full border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">
        @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
        <textarea name="description" rows="4" class="w-full border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $experience->description ?? '') }}</textarea>
        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Início</label>
        <input type="date" name="started_at" value="{{ old('started_at', isset($experience) ? \Carbon\Carbon::parse($experience->started_at)->format('Y-m-d') : '') }}" class="w-full border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">
        @error('started_at') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Fim <span class="text-gray-400 text-xs">(deixe em branco se for atual)</span></label>
        <input type="date" name="ended_at" value="{{ old('ended_at', isset($experience) && $experience->ended_at ? \Carbon\Carbon::parse($experience->ended_at)->format('Y-m-d') : '') }}" class="w-full border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">
        @error('ended_at') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Ordem</label>
        <input type="number" name="order" value="{{ old('order', $experience->order ?? 0) }}" min="0" class="w-full border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">
        @error('order') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
    <div class="flex items-center mt-5">
        <input type="hidden" name="is_visible" value="0">
        <input type="checkbox" id="is_visible" name="is_visible" value="1" {{ old('is_visible', $experience->is_visible ?? true) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded">
        <label for="is_visible" class="ml-2 text-sm text-gray-700">Visível no portfólio</label>
        @error('is_visible') <span class="text-red-500 text-sm ml-2">{{ $message }}</span> @enderror
    </div>
</div>
