<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kategori')->constrained('kategori_alat')->restrictOnDelete();
            $table->string('nama_alat');
            $table->string('deskripsi')->nullable();
            $table->integer('jumlah_total')->default(0);
            $table->integer('jumlah_dipinjam')->default(0);
            $table->integer('jumlah_rusak')->default(0);
            $table->text('path_foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alats');
    }
};
