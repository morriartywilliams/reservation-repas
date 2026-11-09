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
        Schema::create('repas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('date');
            $table->enum('statut', ['planifie', 'servi'])->default('planifie');
            $table->timestamps();

            $table->unique(['menu_id', 'date'], 'uniq_repas_menu_date');
            $table->index('statut', 'idx_repas_statut');
            $table->index('date', 'idx_repas_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repas');
    }
};
