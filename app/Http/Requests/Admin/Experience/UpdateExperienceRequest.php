<?php

namespace App\Http\Requests\Admin\Experience;

use App\Models\Experience;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExperienceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return \Illuminate\Support\Facades\Gate::allows('isAdmin');
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('company')) {
            $this->merge(['company' => strip_tags(trim($this->company))]);
        }
        if ($this->has('role')) {
            $this->merge(['role' => strip_tags(trim($this->role))]);
        }
    }

    public function rules(): array
    {
        return [
            'company' => ['required', 'string', 'max:150'],
            'role' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:3000'],
            'started_at' => ['required', 'date', 'before_or_equal:today'],
            'ended_at' => ['nullable', 'date', 'after:started_at'],
            'is_visible' => ['required', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'string' => 'O campo :attribute deve ser texto.',
            'max' => 'O limite do campo :attribute é :max.',
            'date' => 'O campo :attribute deve ser uma data válida.',
            'before_or_equal' => 'A data de início não pode ser no futuro.',
            'after' => 'A data de término deve ser posterior à data de início.',
            'boolean' => 'O campo :attribute deve ser verdadeiro ou falso.',
            'integer' => 'O campo :attribute deve ser um número inteiro.',
            'min' => 'O valor mínimo para :attribute é :min.',
        ];
    }
}
