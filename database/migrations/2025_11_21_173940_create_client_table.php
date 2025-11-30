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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignID('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type');
            $table->string('firstname');
            $table->string('lastname');
            $table->char('cnp', 13)->nullable()->unique();
            $table->string('cui', 12)->nullable()->unique();
            $table->boolean('tva_payer')->default(false);
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('county')->nullable();
            $table->string('locality')->nullable();
            $table->string('address')->nullable();
            $table->string('status')->default('activ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
