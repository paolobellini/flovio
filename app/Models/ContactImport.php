<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ImportStatus;
use Database\Factories\ContactImportFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property int $user_id
 * @property string $file_name
 * @property string $file_path
 * @property ImportStatus $status
 * @property string $delimiter
 * @property string $name_column
 * @property string $email_column
 * @property int $total_rows
 * @property int $processed_rows
 * @property int $created_count
 * @property int $skipped_count
 * @property int $failed_count
 * @property ?array<int, array<string, string>> $errors
 * @property ?Carbon $completed_at
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property-read User $user
 */
final class ContactImport extends Model
{
    /** @use HasFactory<ContactImportFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'user_id' => 'integer',
            'file_name' => 'string',
            'file_path' => 'string',
            'status' => ImportStatus::class,
            'delimiter' => 'string',
            'name_column' => 'string',
            'email_column' => 'string',
            'total_rows' => 'integer',
            'processed_rows' => 'integer',
            'created_count' => 'integer',
            'skipped_count' => 'integer',
            'failed_count' => 'integer',
            'errors' => 'array',
            'completed_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
