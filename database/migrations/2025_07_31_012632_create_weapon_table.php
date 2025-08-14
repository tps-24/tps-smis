<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('weapons', function (Blueprint $table) {
        
         $table->id();
        $table->string('weapon_id')->unique();
        $table->string('serial_number')->unique();
        $table->string('weapon_type');
        $table->string('category');
        $table->string('make_model');
        $table->string('caliber_gauge')->nullable();
        $table->date('acquisition_date');
        $table->string('condition');
        $table->string('current_status');
        $table->string('location')->nullable();
        $table->text('remarks')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weapon');
    }
};
