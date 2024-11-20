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
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Kolom untuk ID pengguna
            $table->date('date');                // Tanggal aktivitas
            $table->string('name');               // Nama pengguna
            $table->time('start_time');           // Jam mulai
            $table->time('end_time');             // Jam selesai
            $table->text('activity');             // Aktivitas hari ini
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journals');
    }
};
