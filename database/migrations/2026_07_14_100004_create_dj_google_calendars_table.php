<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dj_google_calendars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dj_id')->unique()->constrained('djs')->cascadeOnDelete();
            $table->string('google_account_email')->nullable();
            $table->text('google_access_token')->nullable();
            $table->text('google_refresh_token')->nullable();
            $table->timestamp('token_expiry')->nullable();
            $table->string('calendar_id')->default('primary');
            $table->enum('calendar_sync_status', ['disconnected', 'connected', 'error', 'syncing'])->default('disconnected');
            $table->timestamp('last_synced_at')->nullable();
            $table->text('last_sync_error')->nullable();
            $table->timestamps();

            $table->index('calendar_sync_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dj_google_calendars');
    }
};
