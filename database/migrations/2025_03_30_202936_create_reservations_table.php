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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('showtimes_id')->constrained('showtimes')->onDelete('cascade');
            $table->foreignId('seats_id')->constrained('seats')->onDelete('cascade');
            $table->date('reservation_date');
            $table->timestamps();

            $table->unique(['showtimes_id', 'seats_id', 'reservation_date'], 'unique_seat_reservation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
