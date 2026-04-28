<?php

namespace App\Http\Requests\Admin\AboutMe;

use App\Models\AboutMe;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAboutMeRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Delega para a Policy correspondente
        return $this->user()->can('update', AboutMe::class);
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge(['name' => strip_tags(trim($this->name))]);
        }
        if ($this->has('title')) {
            $this->merge(['title' => strip_tags(trim($this->title))]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:150'],
            'bio' => ['required', 'string', 'max:3000'],
            'email' => ['required', 'email', 'max:150'],
            'location' => ['nullable', 'string', 'max:100'],
            'is_available_for_work' => ['required', 'boolean'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'string' => 'O campo :attribute deve ser um texto válido.',
            'max' => 'O limite máximo para o campo :attribute é :max.',
            'email' => 'O e-mail informado não é válido.',
            'boolean' => 'O campo :attribute deve ser um valor booleano.',
            'image' => 'O arquivo enviado deve ser uma imagem.',
            'mimes' => 'A imagem deve estar em um dos formatos: :values.',
        ];
    }
}
