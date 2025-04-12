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
        Schema::create('reservation_seat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservations_id')->constrained('reservations')->onDelete('cascade');
            $table->foreignId('seats_id')->constrained('seats')->onDelete('cascade');
            $table->timestamps();

            // Prevent double booking the same seat for the same reservation
            $table->unique(['reservations_id', 'seats_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_seat');
    }
};
