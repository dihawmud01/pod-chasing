<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            // prospect_date: tanggal harian prospect dibuat/dijadwalkan
            // Default to created_at date, can be rescheduled by operator
            $table->date('prospect_date')->nullable()->after('id');
        });

        // Isi prospect_date yang null dengan DATE(created_at)
        DB::statement('UPDATE prospects SET prospect_date = DATE(created_at) WHERE prospect_date IS NULL');
    }

    public function down(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->dropColumn('prospect_date');
        });
    }
};
