<?php

namespace App\Http\Requests\Admin\Technology;

use App\Models\Technology;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateTechnologyRequest extends FormRequest
{
    public function authorize(): bool
    {
        $technology = $this->route('technology') ?? $this->route('id') ?? Technology::class;
        return $this->user()->can('update', $technology);
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => strip_tags(trim($this->name)),
                'slug' => Str::slug($this->name),
            ]);
        }
    }

    public function rules(): array
    {
        $id = $this->route('technology') ?? $this->route('id');

        return [
            'name' => [
                'required', 
                'string', 
                'max:50', 
                Rule::unique('technologies', 'name')->ignore($id)
            ],
            'color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'string' => 'O campo :attribute deve ser texto.',
            'max' => 'O limite do campo :attribute é :max.',
            'unique' => 'Esta tecnologia já está cadastrada.',
            'regex' => 'A cor deve estar no formato hexadecimal (ex: #FFFFFF).',
        ];
    }
}
