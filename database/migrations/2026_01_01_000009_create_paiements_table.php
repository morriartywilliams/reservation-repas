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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->unique()->constrained('reservations')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('montant', 8, 2);
            $table->enum('statut', ['paye', 'impaye', 'exonere'])->default('impaye');
            $table->enum('moyen', ['especes', 'carte_cantine'])->nullable();
            $table->date('date')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();

            $table->index('statut', 'idx_paiements_statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
