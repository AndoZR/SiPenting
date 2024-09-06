<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $path = database_path('migrations/indonesia.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql);
        
        // Schema::create('desa', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('nama');
        //     $table->char('id_kecamatan', 7);
        //     $table->foreign('id_kecamatan')->references('kecamatan_id')->on('kecamatan')->onDelete('cascade');
        // });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->char('nik',16);
            $table->string('username');
            $table->string('namaIbu')->nullable();
            $table->date('tanggalLahir')->nullable();
            $table->text('profilImg')->nullable();
            $table->decimal('bbPraHamil')->nullable();
            $table->decimal('tinggiBadan')->nullable();
            // $table->string('email')->unique();
            // $table->timestamp('email_verified_at')->nullable();
            $table->integer('role');
            $table->char('id_villages',10)->nullable();
            $table->foreign('id_villages')->references('id')->on('villages')->onDelete('cascade');
            $table->string('password');
            $table->string('id_subs')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Schema::create('password_reset_tokens', function (Blueprint $table) {
        //     $table->string('email')->primary();
        //     $table->string('token');
        //     $table->timestamp('created_at')->nullable();
        // });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        // Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');

        Schema::dropIfExists('keecamatan');
        Schema::dropIfExists('kabupaten');
        Schema::dropIfExists('provinsi');
    }
};
