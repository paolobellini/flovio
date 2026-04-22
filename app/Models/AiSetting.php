<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\AiSettingFactory;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property int $user_id
 * @property string $chat_model
 * @property string $image_model
 * @property string $content_model
 * @property ?string $openai_api_key
 * @property ?string $anthropic_api_key
 * @property ?string $google_api_key
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property-read User $user
 */
#[Hidden(['openai_api_key', 'anthropic_api_key', 'google_api_key'])]
final class AiSetting extends Model
{
    /** @use HasFactory<AiSettingFactory> */
    use HasFactory;

    /**
     * @var array<string, string>
     */
    protected $attributes = [
        'chat_model' => 'gemini-2.5-flash',
        'image_model' => 'gemini-2.5-flash-image',
        'content_model' => 'gemini-2.5-flash',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'user_id' => 'integer',
            'chat_model' => 'string',
            'image_model' => 'string',
            'content_model' => 'string',
            'openai_api_key' => 'encrypted',
            'anthropic_api_key' => 'encrypted',
            'google_api_key' => 'encrypted',
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
