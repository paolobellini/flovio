<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\ContactImportAnalysis;
use App\Models\ContactImport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Saturio\DuckDB\DuckDB;

final class ContactImportService
{
    private const string EMAIL_REGEX = '^[a-zA-Z0-9._%+\\-]+@[a-zA-Z0-9.\\-]+\\.[a-zA-Z]{2,}$';

    private const string TABLE = 'csv_import';

    public function analyze(ContactImport $import): ContactImportAnalysis
    {
        $db = DuckDB::create();

        $this->createCsvView($db, $import);
        $stats = $this->queryStats($db, $import);

        return new ContactImportAnalysis(
            total: $stats['total'],
            valid: $stats['valid'],
            invalid: $stats['invalid'],
            duplicates: $stats['duplicates'],
            validRows: $this->queryValidRows($db),
        );
    }

    private function createCsvView(DuckDB $db, ContactImport $import): void
    {
        $fullPath = Storage::disk('local')->path($import->file_path);

        $nameCol = $this->quoteIdentifier($import->name_column);
        $emailCol = $this->quoteIdentifier($import->email_column);

        $db->query(sprintf(
            'CREATE TEMP TABLE %s AS
            SELECT
                TRIM(%s) AS name,
                LOWER(TRIM(%s)) AS email,
                ROW_NUMBER() OVER () AS row_num
            FROM read_csv(\'%s\', delim = \'%s\', header = true, ignore_errors = true)
            WHERE %s IS NOT NULL AND TRIM(%s) != \'\'
                AND %s IS NOT NULL AND TRIM(%s) != \'\'',
            self::TABLE,
            $nameCol,
            $emailCol,
            addcslashes($fullPath, "'\\"),
            addcslashes($import->delimiter, "'\\"),
            $nameCol,
            $nameCol,
            $emailCol,
            $emailCol,
        ));
    }

    /**
     * @return array{total: int, valid: int, invalid: int, duplicates: int}
     */
    private function queryStats(DuckDB $db, ContactImport $import): array
    {
        $fullPath = Storage::disk('local')->path($import->file_path);

        $totalResult = $db->query(sprintf(
            "SELECT COUNT(*) AS cnt FROM read_csv('%s', delim = '%s', header = true, ignore_errors = true)",
            addcslashes($fullPath, "'\\"),
            addcslashes($import->delimiter, "'\\"),
        ));

        $total = 0;

        /** @var array<string, int> $row */
        foreach ($totalResult->rows(columnNameAsKey: true) as $row) {
            $total = (int) $row['cnt'];
        }

        $statsResult = $db->query(sprintf(
            'SELECT
                COUNT(*) AS after_null_filter,
                COUNT(*) FILTER (WHERE regexp_matches(email, \'%s\')) AS valid_email,
                COUNT(*) FILTER (WHERE NOT regexp_matches(email, \'%s\')) AS invalid_email
            FROM %s',
            self::EMAIL_REGEX,
            self::EMAIL_REGEX,
            self::TABLE,
        ));

        $afterNullFilter = 0;
        $validEmail = 0;
        $invalidEmail = 0;

        /** @var array<string, int> $row */
        foreach ($statsResult->rows(columnNameAsKey: true) as $row) {
            $afterNullFilter = (int) $row['after_null_filter'];
            $validEmail = (int) $row['valid_email'];
            $invalidEmail = (int) $row['invalid_email'];
        }

        $nullFiltered = $total - $afterNullFilter;

        $dupResult = $db->query(sprintf(
            "SELECT COUNT(*) AS cnt FROM (
                SELECT email, ROW_NUMBER() OVER (PARTITION BY email ORDER BY row_num) AS rn
                FROM %s
                WHERE regexp_matches(email, '%s')
            ) WHERE rn > 1",
            self::TABLE,
            self::EMAIL_REGEX,
        ));

        $duplicates = 0;

        /** @var array<string, int> $row */
        foreach ($dupResult->rows(columnNameAsKey: true) as $row) {
            $duplicates = (int) $row['cnt'];
        }

        return [
            'total' => $total,
            'valid' => $validEmail - $duplicates,
            'invalid' => $nullFiltered + $invalidEmail,
            'duplicates' => $duplicates,
        ];
    }

    /**
     * @return Collection<int, array{name: string, email: string}>
     */
    private function queryValidRows(DuckDB $db): Collection
    {
        $result = $db->query(sprintf(
            "SELECT array_to_string(
                list_transform(
                    string_split(name, ' '),
                    word -> CASE WHEN length(word) > 0
                                 THEN upper(substr(word, 1, 1)) || lower(substr(word, 2))
                                 ELSE word END
                ),
                ' '
            ) AS name, email FROM (
                SELECT name, email, ROW_NUMBER() OVER (PARTITION BY email ORDER BY row_num) AS rn
                FROM %s
                WHERE regexp_matches(email, '%s')
            ) WHERE rn = 1",
            self::TABLE,
            self::EMAIL_REGEX,
        ));

        $rows = [];

        /** @var array{name: string, email: string} $row */
        foreach ($result->rows(columnNameAsKey: true) as $row) {
            $rows[] = $row;
        }

        return Collection::make($rows);
    }

    private function quoteIdentifier(string $identifier): string
    {
        return '"'.str_replace('"', '""', $identifier).'"';
    }
}
