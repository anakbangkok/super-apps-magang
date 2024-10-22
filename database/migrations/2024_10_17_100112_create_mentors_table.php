<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('mentors', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('nama');
            $table->enum('jabatan', ['Manajer', 'SPV', 'Staff']);
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('mentors');
    }
    
};