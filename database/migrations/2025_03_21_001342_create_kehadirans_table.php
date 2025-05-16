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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->string('shift'); 
            $table->date('date'); 
            $table->time('check_in')->nullable(); 
            $table->time('check_out')->nullable(); 
            $table->string('location'); 
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('kehadirans');
    }
}
