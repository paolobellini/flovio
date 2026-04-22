<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Actions\UpdateAiSettingAction;
use App\Http\Requests\Settings\AiSettingRequest;
use App\Models\User;
use Flux\Flux;
use Illuminate\Container\Attributes\CurrentUser;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('AI settings')]
final class AiSettings extends Component
{
    public string $chat_model = '';

    public string $image_model = '';

    public string $content_model = '';

    public string $openai_api_key = '';

    public string $anthropic_api_key = '';

    public string $google_api_key = '';

    public function mount(#[CurrentUser] User $user): void
    {
        $aiSetting = $user->aiSetting;

        $this->chat_model = $aiSetting->chat_model;
        $this->image_model = $aiSetting->image_model;
        $this->content_model = $aiSetting->content_model;
        $this->openai_api_key = $aiSetting->openai_api_key ?? '';
        $this->anthropic_api_key = $aiSetting->anthropic_api_key ?? '';
        $this->google_api_key = $aiSetting->google_api_key ?? '';
    }

    public function updateAiSettings(#[CurrentUser] User $user, UpdateAiSettingAction $action): void
    {
        /** @var array<string, mixed> $validated */
        $validated = $this->validate((new AiSettingRequest())->rules());

        $action->handle($user, $validated);

        Flux::toast(variant: 'success', text: __('AI settings updated.'));
    }
}
