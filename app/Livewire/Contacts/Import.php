<?php

declare(strict_types=1);

namespace App\Livewire\Contacts;

use App\Actions\StoreContactImportAction;
use App\Http\Requests\ContactImportRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

final class Import extends Component
{
    use WithFileUploads;

    public int $step = 1;

    #[Validate('required|file|mimes:csv,txt|max:5120')]
    public TemporaryUploadedFile $file;

    /** @var array<int, string> */
    public array $headers = [];

    /** @var array<int, array<int, string>> */
    public array $previewRows = [];

    public string $nameColumn = '';

    public string $emailColumn = '';

    public string $delimiter = ',';

    public int $totalRows = 0;

    public int $createdCount = 0;

    public int $skippedCount = 0;

    public int $failedCount = 0;

    public function updatedFile(): void
    {
        $this->validateOnly('file');

        $this->parsePreview();
    }

    public function preview(): void
    {
        $this->validate();

        if ($this->headers === []) {
            $this->parsePreview();
        }

        $this->step = 2;
    }

    public function import(#[CurrentUser] User $user, StoreContactImportAction $action): void
    {
        /** @var array<string, mixed> $validated */
        $validated = $this->validate(new ContactImportRequest()->rules());

        $action->handle($user, $this->file, $validated);

        $this->step = 3;
    }

    public function resetImport(): void
    {
        $this->reset();
        $this->resetErrorBag();
    }

    public function render(): View
    {
        return view('livewire.contacts.import');
    }

    private function parsePreview(): void
    {
        $path = $this->file->getRealPath();

        $this->delimiter = $this->detectDelimiter($path);

        $handle = fopen($path, 'r');

        if ($handle === false) {
            return;
        }

        $headerRow = fgetcsv($handle, separator: $this->delimiter);

        if ($headerRow === false || $headerRow === [null]) {
            fclose($handle);

            return;
        }

        $this->headers = array_values(array_filter(
            array_map(static fn (?string $v): string => trim((string) $v), $headerRow),
            static fn (string $v): bool => $v !== '',
        ));
        $this->autoMapColumns();

        $rows = [];
        $count = 0;
        $columnCount = count($this->headers);

        while (($row = fgetcsv($handle, separator: $this->delimiter)) !== false && count($rows) < 5) {
            if ($row === [null]) {
                continue;
            }

            $rows[] = array_slice(
                array_map(static fn (?string $v): string => trim((string) $v), $row),
                0,
                $columnCount,
            );
            $count++;
        }

        while (fgetcsv($handle, separator: $this->delimiter) !== false) {
            $count++;
        }

        fclose($handle);

        $this->previewRows = $rows;
        $this->totalRows = $count;
    }

    private function detectDelimiter(string $path): string
    {
        $firstLine = (string) fgets(fopen($path, 'r') ?: throw new \RuntimeException('Cannot open file'));

        $delimiters = [',' => 0, ';' => 0, "\t" => 0, '|' => 0];

        foreach (array_keys($delimiters) as $delimiter) {
            $delimiters[$delimiter] = substr_count($firstLine, $delimiter);
        }

        return (string) array_key_first(array_filter($delimiters, static fn (int $count): bool => $count === max($delimiters)));
    }

    private function autoMapColumns(): void
    {
        foreach ($this->headers as $header) {
            $lower = mb_strtolower($header);

            if (in_array($lower, ['name', 'nome', 'full_name', 'fullname', 'full name'], true)) {
                $this->nameColumn = $header;
            }

            if (in_array($lower, ['email', 'e-mail', 'email_address', 'email address'], true)) {
                $this->emailColumn = $header;
            }
        }
    }
}
