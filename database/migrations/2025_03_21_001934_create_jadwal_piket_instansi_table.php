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
        Schema::create('jadwal_piket_instansi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jadwal_piket_id');
            $table->unsignedBigInteger('instansi_id');
            $table->timestamps();
    

            $table->foreign('jadwal_piket_id')->references('id')->on('jadwal_pikets')->onDelete('cascade');
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('cascade');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_piket_instansi');
    }
};
