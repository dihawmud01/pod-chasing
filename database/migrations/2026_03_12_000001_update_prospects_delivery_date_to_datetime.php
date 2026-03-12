<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change delivery_date from DATE to DATETIME
        DB::statement('ALTER TABLE prospects MODIFY delivery_date DATETIME NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE prospects MODIFY delivery_date DATE NULL');
    }
};
