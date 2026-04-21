<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('detail_pengembalian', function (Blueprint $table) {
            $table->id();
            // FK to detail_pinjaman.id
            $table->foreignId('id_detail_pinjaman')->constrained('detail_pinjaman')->cascadeOnDelete();
            // keep a copy of the detail code for readability
            $table->string('id_detail_pinjaman_code')->nullable();
            $table->integer('jumlah_kembali')->default(1);
            $table->date('tanggal_kembali')->nullable();
            $table->enum('kondisi', ['baik','rusak','hilang'])->default('baik');
            $table->bigInteger('denda')->default(0);
            $table->text('pesan')->nullable();
            // store who received the return; kept as string per request
            $table->string('diterima_oleh')->nullable();

            $table->timestamps();

            $table->index('id_detail_pinjaman');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pengembalian');
    }
};
