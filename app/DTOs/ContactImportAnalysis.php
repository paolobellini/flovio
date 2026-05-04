<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class ContactImportAnalysis
{
    /**
     * @param  iterable<int, array{name: string, email: string}>  $validRows
     */
    public function __construct(
        public int $total,
        public int $valid,
        public int $invalid,
        public int $duplicates,
        public iterable $validRows,
    ) {}
}
