<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prospects', function (Blueprint $table) {
            $table->id();
            $table->string('vessel_name');
            $table->string('port')->nullable();
            $table->date('eta')->nullable();
            $table->date('etb')->nullable();
            $table->date('etd')->nullable();
            $table->string('destination_country')->nullable();
            $table->string('forwarder')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('status')->default('planning');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prospects');
    }
};
