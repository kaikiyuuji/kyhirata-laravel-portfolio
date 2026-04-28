<?php

namespace App\Http\Requests\Admin\Project;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Project::class);
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('title')) {
            $this->merge([
                'title' => strip_tags(trim($this->title)),
                'slug' => Str::slug($this->title),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['required', 'string', 'max:255', 'unique:projects,slug'],
            'description' => ['required', 'string', 'max:5000'],
            'github_url' => ['nullable', 'url', 'max:500', 'starts_with:https://github.com'],
            'demo_url' => ['nullable', 'url', 'max:500'],
            'show_project_button' => ['required', 'boolean'],
            'is_visible' => ['required', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
            'technology_ids' => ['nullable', 'array'],
            'technology_ids.*' => ['exists:technologies,id'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'string' => 'O campo :attribute deve ser texto.',
            'max' => 'O limite do campo :attribute é :max.',
            'url' => 'O campo :attribute deve ser uma URL válida.',
            'starts_with' => 'A URL do GitHub deve começar com https://github.com.',
            'boolean' => 'O campo :attribute deve ser verdadeiro ou falso.',
            'integer' => 'O campo :attribute deve ser um número inteiro.',
            'min' => 'O valor mínimo para :attribute é :min.',
            'array' => 'As tecnologias devem estar em formato de lista.',
            'exists' => 'Uma ou mais tecnologias selecionadas são inválidas.',
            'image' => 'O thumbnail deve ser uma imagem válida.',
        ];
    }
}
