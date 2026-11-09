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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 150);
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('statut', ['brouillon', 'publie'])->default('brouillon');
            $table->foreignId('created_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();

            $table->index('statut', 'idx_menus_statut');
            $table->index(['date_debut', 'date_fin'], 'idx_menus_dates');
        });

        // Add check constraint for date_fin >= date_debut if supported
        if (config('database.default') === 'mysql') {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE menus ADD CONSTRAINT chk_menus_dates CHECK (date_fin >= date_debut)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
