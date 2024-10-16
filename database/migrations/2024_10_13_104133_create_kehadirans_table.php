<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKehadiransTable extends Migration
{
    public function up()
    {
        Schema::create('kehadirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key ke users
            $table->string('shift'); // Shift: Pagi atau Sore
            $table->date('date'); // Tanggal absen
            $table->time('check_in')->nullable(); // Waktu masuk
            $table->time('check_out')->nullable(); // Waktu pulang
            $table->string('location'); // Lokasi absen
            $table->timestamps(); // Timestamps created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('kehadirans');
    }
}
