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
        Schema::create('pivot_puskesmas_village', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('puskesmas_id');
            $table->string('village_id'); // karena id desa format string seperti '3511100001'
            $table->timestamps();

            // Foreign key constraints (jika tabel puskesmas dan villages sudah ada)
            $table->foreign('puskesmas_id')->references('id')->on('akun_puskesmas')->onDelete('cascade');
            $table->foreign('village_id')->references('id')->on('villages')->onDelete('cascade');

            // Index untuk pencarian cepat
            $table->unique(['puskesmas_id', 'village_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pivot_puskesmas_village');
    }
};
