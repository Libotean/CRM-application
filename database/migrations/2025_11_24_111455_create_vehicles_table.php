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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            // --- RELAȚII ---
            $table->foreignId('vehicle_make_id')->constrained();
            $table->foreignId('vehicle_model_id')->constrained();

            // AICI LEGĂM DE TABELA TA EXISTENTĂ 'clients'
            // Este nullable() pentru că atunci când aduci mașina, nu e vândută nimănui.
            $table->foreignId('client_id')
                ->nullable()
                ->constrained('clients') // Specificăm explicit numele tabelei tale
                ->nullOnDelete();        // Dacă ștergi clientul, mașina rămâne în istoric (fără proprietar)

            // --- IDENTIFICARE STOC ---
            $table->string('internal_catalog_number')->unique()->nullable(); // 110001
            $table->string('vin')->unique()->nullable(); // Serie Șasiu

            // --- DETALII TEHNICE ---
            $table->string('version_name')->nullable(); // Ex: 250+
            $table->integer('power_hp')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('transmission')->nullable();
            $table->string('traction')->nullable();
            $table->string('color')->nullable();
            $table->integer('manufacturing_year')->nullable();
            $table->integer('mileage')->default(0);

            // --- STARE și PREȚ ---
            $table->enum('condition', ['nou', 'rulat'])->default('rulat');
            $table->date('stock_entry_date')->nullable();

            $table->decimal('price_eur', 10, 2)->nullable();      // Preț Vânzare
            $table->decimal('old_price_eur', 10, 2)->nullable();  // Preț Vechi (tăiat)
            $table->boolean('vat_deductible')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
