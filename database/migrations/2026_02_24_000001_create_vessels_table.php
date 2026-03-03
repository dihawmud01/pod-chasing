<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vessels', function (Blueprint $table) {
            $table->id();
            $table->string('vessel_name');
            $table->string('driver')->nullable();
            $table->string('delivery_address')->nullable();
            $table->text('information')->nullable();
            $table->boolean('customs_doc')->default(false);
            $table->boolean('print_status')->default(false);
            $table->boolean('pod_status')->default(false);
            $table->boolean('delivered')->default(false);
            $table->string('pod_file')->nullable();
            $table->date('report_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vessels');
    }
};
