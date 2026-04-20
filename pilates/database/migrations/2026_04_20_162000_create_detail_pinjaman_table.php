<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('detail_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pinjaman')->constrained('pinjaman')->cascadeOnDelete();
            $table->foreignId('id_alat')->constrained('alats')->cascadeOnDelete();
            $table->integer('jumlah')->default(1);
            $table->enum('status', ['pending','active','returned','cancelled'])->default('pending');
            $table->timestamps();

            $table->index('id_pinjaman');
            $table->index('id_alat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pinjaman');
    }
};
