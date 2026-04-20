<?php

declare(strict_types=1);

namespace App\Http\Requests\Onboarding;

use Illuminate\Foundation\Http\FormRequest;

final class SmtpSettingRequest extends FormRequest
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
            'mailgun_api_key' => ['required', 'string', 'min:10', 'max:255'],
            'mailgun_domain' => ['required', 'string', 'min:3', 'max:255'],
            'sender_name' => ['required', 'string', 'min:2', 'max:100'],
            'sender_email' => ['required', 'email', 'max:255'],
        ];
    }
}
