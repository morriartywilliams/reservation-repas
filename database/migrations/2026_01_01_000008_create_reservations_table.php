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
            $table->foreignId('eleve_id')->constrained('eleves')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('repas_id')->constrained('repas')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('status', ['en_attente', 'confirmee', 'annulee'])->default('confirmee');
            $table->enum('attendance_status', ['non_verifiee', 'present', 'absent'])->default('non_verifiee');
            $table->timestamp('date_reservation')->useCurrent();
            $table->timestamps();

            $table->unique(['eleve_id', 'repas_id'], 'uniq_reservation_eleve_repas');
            $table->index('status', 'idx_reservations_status');
            $table->index('attendance_status', 'idx_reservations_attendance');
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
