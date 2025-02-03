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
        Schema::create('beats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('beatType_id');
            $table->unsignedBigInteger('area_id');
            $table->string('student_id');
            $table->integer('round');
            $table->date('date');
            $table->time('start_at');
            $table->time('end_at')->nullable();
            $table->boolean('status')->nullable();
            $table->foreign('area_id')->references('id')->on('areas')->onupdate('update')->ondelete('null');
            //$table->foreign('beatType_id')->references('id')->on('beat_types')->onupdate('update')->ondelete('null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beats');
    }
};
