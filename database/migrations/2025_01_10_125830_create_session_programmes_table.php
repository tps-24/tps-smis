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
        Schema::create('session_programmes', function (Blueprint $table) {
            $table->id();
            $table->string('programme_name', 100);
            $table->text('description', 200);
            $table->text('year', 20);
            $table->tinyInteger('is_current');
            $table->tinyInteger('is_active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_programmes');
    }
};
