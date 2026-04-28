<?php

namespace App\Http\Requests\Admin\SocialLink;

use App\Models\SocialLink;
use Illuminate\Foundation\Http\FormRequest;

class StoreSocialLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', SocialLink::class);
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('platform')) {
            $this->merge(['platform' => strip_tags(trim($this->platform))]);
        }
    }

    public function rules(): array
    {
        return [
            'platform' => ['required', 'string', 'max:50'],
            'url' => ['required', 'url', 'max:500'],
            'icon' => ['required', 'string', 'max:50'],
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
            'url' => 'A URL informada não é válida.',
            'boolean' => 'O campo :attribute deve ser verdadeiro ou falso.',
            'integer' => 'O campo :attribute deve ser um número inteiro.',
            'min' => 'O valor mínimo para :attribute é :min.',
        ];
    }
}
