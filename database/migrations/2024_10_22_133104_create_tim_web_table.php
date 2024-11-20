<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimWebTable extends Migration
{
    public function up()
    {
        Schema::create('tim_web', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Menambahkan kolom user_id sebagai foreign key
            $table->integer('jumlah_artikel')->default(0);
            $table->integer('jumlah_kata')->default(0);
            $table->text('keterangan');
            $table->date('tanggal');
            $table->timestamps();
            
            // Membuat foreign key constraint untuk user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tim_web');
    }
}
