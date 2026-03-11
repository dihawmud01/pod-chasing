<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add section column: 'nl_be' or 'eu_gb'
        DB::statement("ALTER TABLE prospects ADD COLUMN section ENUM('nl_be','eu_gb') NOT NULL DEFAULT 'nl_be' AFTER prospect_date");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE prospects DROP COLUMN section');
    }
};
