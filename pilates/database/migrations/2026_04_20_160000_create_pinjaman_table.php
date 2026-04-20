<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->id();
            $table->string('id_peminjam')->constrained('users')->restrictOnDelete();
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->string('disetujui_oleh')->constrained('users')->restrictOnDelete();
            $table->date('tanggal_selesai');
            $table->bigInteger('total_denda')->default(0);
            $table->text('pesan')->nullable();
            $table->string('diselesaikan_oleh')->constrained('users')->restrictOnDelete();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->index('id_peminjam');
            $table->index('disetujui_oleh');
            $table->index('diselesaikan_oleh');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};
