<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Add new statuses 'kembali_sebagian' and 'selesai' to enum
        // Using raw SQL since altering enum needs explicit definition
        if (Schema::hasTable('detail_pinjaman')) {
            DB::statement("ALTER TABLE detail_pinjaman MODIFY COLUMN `status` ENUM('dipinjam','kembali_sebagian','selesai') NOT NULL DEFAULT 'dipinjam'");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('detail_pinjaman')) {
            DB::statement("ALTER TABLE detail_pinjaman MODIFY COLUMN `status` ENUM('pending','active','returned','cancelled') NOT NULL DEFAULT 'pending'");
        }
    }
};
