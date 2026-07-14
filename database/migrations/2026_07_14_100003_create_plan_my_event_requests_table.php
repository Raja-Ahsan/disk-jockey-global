<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_my_event_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('client_name');
            $table->string('city');
            $table->string('zipcode', 20);
            $table->date('event_date');
            $table->string('event_type');
            $table->string('venue_type');
            $table->string('venue_type_other')->nullable();
            $table->string('venue_name');
            $table->string('venue_address', 500);
            $table->string('budget_range')->nullable();
            $table->decimal('budget_min', 10, 2)->nullable();
            $table->decimal('budget_max', 10, 2)->nullable();
            $table->string('dj_name')->nullable();
            $table->boolean('use_near_me')->default(false);
            $table->boolean('rush_guarantee')->default(false);
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('special_requests')->nullable();
            $table->enum('status', ['new', 'in_progress', 'contacted', 'closed', 'cancelled'])->default('new');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('event_date');
            $table->index('created_at');
            $table->index(['client_name', 'city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_my_event_requests');
    }
};
