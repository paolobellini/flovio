<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('contact_mailing_list', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained();
            $table->foreignId('mailing_list_id')->constrained();
            $table->timestamps();

            $table->unique(['contact_id', 'mailing_list_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_mailing_list');
    }
};
