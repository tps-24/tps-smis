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
        Schema::create('patrol_areas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('start_area');
            $table->unsignedBigInteger('end_area');
            $table->integer('number_of_guards');
            $table->foreign('company_id')->references('id')->on('companies')->onupdate('update')->ondelete('null');
            $table->foreign('end_area')->references('id')->on('areas')->onupdate('update')->ondelete('null');
            $table->foreign('start_area')->references('id')->on('areas')->onupdate('update')->ondelete('null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patrol_areas');
    }
};
