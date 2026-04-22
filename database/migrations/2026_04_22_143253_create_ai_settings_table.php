<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('ai_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('chat_model')->default('gemini-2.5-flash');
            $table->string('image_model')->default('gemini-2.5-flash-image');
            $table->string('content_model')->default('gemini-2.5-flash');
            $table->text('openai_api_key')->nullable();
            $table->text('anthropic_api_key')->nullable();
            $table->text('google_api_key')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_settings');
    }
};
