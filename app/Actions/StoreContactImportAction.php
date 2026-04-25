<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\ContactImport;
use App\Models\User;
use Illuminate\Http\UploadedFile;

final readonly class StoreContactImportAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $user, UploadedFile $file, array $data): ContactImport
    {
        $path = $file->storeAs(
            path: 'imports',
            name: uniqid('contacts_').'.csv',
        );

        return ContactImport::create([
            'user_id' => $user->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'delimiter' => $data['delimiter'],
            'name_column' => $data['nameColumn'],
            'email_column' => $data['emailColumn'],
            'total_rows' => $data['totalRows'],
        ]);
    }
}
