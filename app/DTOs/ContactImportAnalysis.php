<?php

declare(strict_types=1);

namespace App\DTOs;

use Illuminate\Support\Collection;

final readonly class ContactImportAnalysis
{
    /**
     * @param  Collection<int, array{name: string, email: string}>  $validRows
     */
    public function __construct(
        public int $total,
        public int $valid,
        public int $invalid,
        public int $duplicates,
        public Collection $validRows,
    ) {}
}
