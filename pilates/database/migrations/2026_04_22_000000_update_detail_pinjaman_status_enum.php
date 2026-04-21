<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('detail_pinjaman')) {
            // Add 'pending' and 'disetujui' statuses to support request lifecycle
            DB::statement("ALTER TABLE detail_pinjaman MODIFY COLUMN `status` ENUM('pending','disetujui','dipinjam','kembali_sebagian','selesai') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('detail_pinjaman')) {
            DB::statement("ALTER TABLE detail_pinjaman MODIFY COLUMN `status` ENUM('dipinjam','kembali_sebagian','selesai') NOT NULL DEFAULT 'dipinjam'");
        }
    }
};
