<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToFeedbacksTable extends Migration
{
    public function up()
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->after('id')->nullable(); // Menambahkan kolom user_id
        });
    }

    public function down()
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Menghapus foreign key
            $table->dropColumn('user_id'); // Menghapus kolom user_id
        });
    }
}

