<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\MailingListObserver;
use Database\Factories\MailingListFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property string $name
 * @property ?string $description
 * @property string $icon
 * @property string $color
 * @property bool $is_ai_generated
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property-read Collection<int, Contact> $contacts
 */
#[ObservedBy(MailingListObserver::class)]
final class MailingList extends Model
{
    /** @use HasFactory<MailingListFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'name' => 'string',
            'description' => 'string',
            'icon' => 'string',
            'color' => 'string',
            'is_ai_generated' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @param  Builder<MailingList>  $query
     */
    protected function scopeSearch(Builder $query, string $term): void
    {
        $query->where('name', 'like', "{$term}%");
    }

    /**
     * @return BelongsToMany<Contact, $this>
     */
    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class)->withTimestamps();
    }
}
