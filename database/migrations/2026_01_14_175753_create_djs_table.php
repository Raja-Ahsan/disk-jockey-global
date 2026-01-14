<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('djs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('stage_name');
            $table->text('bio')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zipcode');
            $table->decimal('hourly_rate', 10, 2);
            $table->integer('experience_years')->default(0);
            $table->json('specialties')->nullable(); // Wedding, Corporate, Nightlife, etc.
            $table->json('genres')->nullable(); // Hip Hop, EDM, Rock, etc.
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->json('social_links')->nullable();
            $table->text('equipment')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_available')->default(true);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->integer('total_bookings')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('djs');
    }
};
