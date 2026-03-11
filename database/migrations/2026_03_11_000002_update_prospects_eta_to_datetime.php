<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change eta, etb, etd from DATE to DATETIME (preserves existing values)
        DB::statement('ALTER TABLE prospects MODIFY COLUMN eta DATETIME NULL');
        DB::statement('ALTER TABLE prospects MODIFY COLUMN etb DATETIME NULL');
        DB::statement('ALTER TABLE prospects MODIFY COLUMN etd DATETIME NULL');

        // Add customs_note: free-text field shown when status = 'customs'
        DB::statement('ALTER TABLE prospects ADD COLUMN customs_note VARCHAR(500) NULL AFTER status');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE prospects MODIFY COLUMN eta DATE NULL');
        DB::statement('ALTER TABLE prospects MODIFY COLUMN etb DATE NULL');
        DB::statement('ALTER TABLE prospects MODIFY COLUMN etd DATE NULL');
        DB::statement('ALTER TABLE prospects DROP COLUMN customs_note');
    }
};
