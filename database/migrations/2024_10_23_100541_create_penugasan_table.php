<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenugasanTable extends Migration
{
    public function up()
    {
        Schema::create('penugasan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_unit_bisnis');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penugasan');
    }
}
