<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE teachers MODIFY position ENUM('Guru', 'Wali Kelas', 'Kepala Sekolah', 'Kesiswaan', 'Kurikulum', 'Humas', 'TU') DEFAULT 'Guru'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE teachers MODIFY position VARCHAR(255)");
    }
};
