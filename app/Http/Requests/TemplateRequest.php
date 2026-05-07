<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\TemplateLayout;
use App\Enums\TemplateTone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class TemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, Enum|string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'primary_color' => ['required', 'string', 'max:20'],
            'layout' => ['required', new Enum(TemplateLayout::class)],
            'tone' => ['required', new Enum(TemplateTone::class)],
        ];
    }
}
