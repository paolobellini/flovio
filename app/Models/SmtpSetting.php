<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\SmtpSettingFactory;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property int $user_id
 * @property string $api_key
 * @property string $domain
 * @property string $sender_name
 * @property string $sender_email
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property-read User $user
 */
#[Hidden(['api_key'])]
final class SmtpSetting extends Model
{
    /** @use HasFactory<SmtpSettingFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'user_id' => 'integer',
            'api_key' => 'encrypted',
            'domain' => 'string',
            'sender_name' => 'string',
            'sender_email' => 'string',
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
