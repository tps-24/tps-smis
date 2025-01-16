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
        Schema::create('semester_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained(); 
            $table->foreignId('semester_id')->constrained(); // Link to semesters table 
            $table->date('exam_date')->nullable; 
            $table->integer('max_score');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semester_exams');
    }
};
