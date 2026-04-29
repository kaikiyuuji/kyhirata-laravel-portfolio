<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Empresa</label>
        <input type="text" name="company" value="{{ old('company', $experience->company ?? '') }}" class="w-full border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm transition">
        @error('company') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Cargo</label>
        <input type="text" name="role" value="{{ old('role', $experience->role ?? '') }}" class="w-full border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm transition">
        @error('role') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Descrição</label>
        <textarea name="description" rows="4" class="w-full border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm transition">{{ old('description', $experience->description ?? '') }}</textarea>
        @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Início</label>
        <input type="date" name="started_at" value="{{ old('started_at', isset($experience) ? \Carbon\Carbon::parse($experience->started_at)->format('Y-m-d') : '') }}" class="w-full border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm transition">
        @error('started_at') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Fim <span class="text-slate-400 text-xs font-normal">(deixe em branco se for atual)</span></label>
        <input type="date" name="ended_at" value="{{ old('ended_at', isset($experience) && $experience->ended_at ? \Carbon\Carbon::parse($experience->ended_at)->format('Y-m-d') : '') }}" class="w-full border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm transition">
        @error('ended_at') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Ordem</label>
        <input type="number" name="order" value="{{ old('order', $experience->order ?? 0) }}" min="0" class="w-full border border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 p-2.5 text-sm transition">
        @error('order') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>
    <div class="flex items-center gap-3 mt-5">
        <input type="hidden" name="is_visible" value="0">
        <input type="checkbox" id="is_visible" name="is_visible" value="1" {{ old('is_visible', $experience->is_visible ?? true) ? 'checked' : '' }} class="h-5 w-5 text-blue-600 focus:ring-blue-500/40 border-slate-300 rounded-md transition">
        <label for="is_visible" class="text-sm font-medium text-slate-700">Visível no portfólio</label>
        @error('is_visible') <span class="text-red-500 text-xs ml-2">{{ $message }}</span> @enderror
    </div>
</div>
