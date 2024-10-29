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

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
        
 
            $table->unsignedBigInteger('instansi_id')->nullable();
            $table->unsignedBigInteger('penugasan_id')->nullable();
            $table->unsignedBigInteger('mentor_id')->nullable(); 
        
    
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
        
            $table->timestamps();
        });

        // Schema::create('password_reset_tokens', function (Blueprint $table) {
        //     $table->string('email')->primary();
        //     $table->string('token');
        //     $table->timestamp('created_at')->nullable();
        // });

        // Schema::create('sessions', function (Blueprint $table) {
        //     $table->string('id')->primary();
        //     $table->foreignId('user_id')->nullable()->index();
        //     $table->string('ip_address', 45)->nullable();
        //     $table->text('user_agent')->nullable();
        //     $table->longText('payload');
        //     $table->integer('last_activity')->index();
        // });

        // // Buat tabel instansis
        // Schema::create('instansis', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('nama_instansi');
        //     $table->unsignedBigInteger('instansi_id')->nullable(); // Tambahkan kolom foreign key
        //     $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('cascade'); // Definisikan relasi ke tabel 'instansi'
        //     $table->timestamps();
        // });
        // // Buat tabel penugasans
        // Schema::create('penugasans', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('nama_unit_bisnis');
        //     $table->unsignedBigInteger('penugasan_id')->nullable(); // Tambahkan kolom user_id sebagai foreign key
        //     $table->foreign('penugasan_id')->references('id')->on('penugasans')->onDelete('cascade'); // Definisikan relasi ke tabel 'users'
        //     $table->timestamps();
        // });
        // // Buat tabel mentors
        // Schema::create('mentors', function (Blueprint $table) {
        //     $table->id();
        //     $table->integer('nik');
        //     $table->string('nama');
        //     $table->string('jabatan');
        //     $table->unsignedBigInteger('mentors_id')->unique();  // Kolom user_id sebagai foreign key dan pastikan unik untuk one-to-one
        //     $table->foreign('mentors_id')->references('id')->on('mentors')->onDelete('cascade');  // Definisikan relasi ke tabel 'users'
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentors');
        Schema::dropIfExists('penugasans');
        Schema::dropIfExists('instansis');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
