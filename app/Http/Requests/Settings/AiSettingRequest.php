<?php

declare(strict_types=1);

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

final class AiSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'chat_model' => ['required', 'string', 'max:255'],
            'image_model' => ['required', 'string', 'max:255'],
            'content_model' => ['required', 'string', 'max:255'],
            'openai_api_key' => ['nullable', 'string', 'max:255'],
            'anthropic_api_key' => ['nullable', 'string', 'max:255'],
            'google_api_key' => ['nullable', 'string', 'max:255'],
        ];
    }
}
