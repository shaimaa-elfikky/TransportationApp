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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->foreignId('driver_id');
            $table->foreignId('vehicle_id');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('origin');
            $table->string('destination');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('status', ['scheduled', 'started', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
