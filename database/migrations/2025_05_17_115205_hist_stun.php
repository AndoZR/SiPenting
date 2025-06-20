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
        Schema::create('hist_stun', function (Blueprint $table) {
            $table->id();
            $table->string('jenis');
            $table->date('tanggal');
            $table->unsignedBigInteger('id_bayi');
            $table->foreign('id_bayi')->references('id')->on('bayi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hist_stun');
    }
};
