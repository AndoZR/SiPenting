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
        Schema::create('data_stunt', function (Blueprint $table) {
            $table->id();
            $table->integer('Umur (bulan)');
            $table->decimal('Panjang Badan (cm) -3 SD');
            $table->decimal('Panjang Badan (cm) -2 SD');
            $table->decimal('Panjang Badan (cm) -1 SD');
            $table->decimal('Panjang Badan (cm) Median');
            $table->decimal('Panjang Badan (cm) +1 SD');
            $table->decimal('Panjang Badan (cm) +2 SD');
            $table->decimal('Panjang Badan (cm) +3 SD');
            $table->string('kelamin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dataStunt');
    }
};
