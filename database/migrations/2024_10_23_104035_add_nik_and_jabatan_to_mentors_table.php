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
        Schema::table('mentors', function (Blueprint $table) {
            $table->string('nik')->nullable(); // Menambahkan kolom NIK
            $table->string('jabatan')->nullable(); // Menambahkan kolom jabatan
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::table('mentors', function (Blueprint $table) {
            $table->dropColumn(['nik', 'jabatan']); // Menghapus kolom jika dibalik
        });
    }
};
