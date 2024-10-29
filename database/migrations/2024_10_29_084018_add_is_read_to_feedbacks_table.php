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
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->boolean('is_read')->default(false); // Default belum dibaca
        });
    }
    
    public function down()
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropColumn('is_read');
        });
    }
    
};
