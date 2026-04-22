<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContactStatus;
use App\Observers\ContactObserver;
use Database\Factories\ContactFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property string $email
 * @property string $name
 * @property ContactStatus $status
 * @property ?Carbon $subscribed_at
 * @property ?Carbon $unsubscribed_at
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
#[ObservedBy(ContactObserver::class)]
final class Contact extends Model
{
    /** @use HasFactory<ContactFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'email' => 'string',
            'name' => 'string',
            'status' => ContactStatus::class,
            'subscribed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @param  Builder<Contact>  $query
     */
    protected function scopeSearch(Builder $query, string $term): void
    {
        $lower = mb_strtolower($term);

        $query->where(fn (Builder $q) => $q
            ->whereRaw('LOWER(name) like ?', ["{$lower}%"])
            ->orWhereRaw('LOWER(email) like ?', ["{$lower}%"]));
    }

    /**
     * @param  Builder<Contact>  $query
     */
    protected function scopeStatus(Builder $query, string $status): void
    {
        $query->where('status', $status);
    }

    public function isSubscribed(): bool
    {
        return $this->status === ContactStatus::Subscribed;
    }
}
