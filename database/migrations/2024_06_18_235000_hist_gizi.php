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
        Schema::create('hist_gizi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->text('nilai_gizi');
            $table->unsignedBigInteger('id_bayi');
            $table->foreign('id_bayi')->references('id')->on('bayi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hist_gizi');
    }
};
