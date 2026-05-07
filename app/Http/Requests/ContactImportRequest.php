<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ContactImportRequest extends FormRequest
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
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
            'nameColumn' => ['required', 'string'],
            'emailColumn' => ['required', 'string'],
            'delimiter' => ['required', 'string', 'max:1'],
            'totalRows' => ['required', 'integer', 'min:1'],
        ];
    }
}
