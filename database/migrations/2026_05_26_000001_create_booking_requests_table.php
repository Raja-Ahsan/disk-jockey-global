<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('dj_id')->constrained('djs')->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();

            $table->string('client_name');
            $table->string('event_type');
            $table->date('event_date');
            $table->string('venue_type');
            $table->string('venue_type_other')->nullable();
            $table->string('venue_name');
            $table->string('venue_address');
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('zipcode');
            $table->decimal('budget_min', 10, 2)->nullable();
            $table->decimal('budget_max', 10, 2)->nullable();
            $table->boolean('rush_guarantee')->default(false);
            $table->decimal('rush_fee', 10, 2)->default(0);
            $table->decimal('booking_fee', 10, 2)->default(100);
            $table->decimal('dj_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->text('special_requests')->nullable();

            $table->string('status')->default('pending');
            $table->string('dj_response')->default('pending');
            $table->string('response_token', 64)->unique();
            $table->timestamp('expires_at');
            $table->timestamp('dj_responded_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_requests');
    }
};
