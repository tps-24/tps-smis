<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('weapons', function (Blueprint $table) {
        $table->id();
        $table->string('serial_number');
        $table->string('specification')->nullable();
        $table->string('category'); // New
        $table->string('weapon_model'); // New
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weapons');
    }
};
