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
        Schema::create('pengajuan_izin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi dengan tabel user
            $table->string('durasi'); // sehari, setengah hari, lebih sehari
            $table->string('jenis_izin'); // sakit, keluarga, kegiatan sekolah
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->integer('jumlah_hari'); // otomatis dihitung
            $table->text('keterangan'); // penjelasan alasan izin
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu'); // status pengajuan
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_izin');
    }
};
