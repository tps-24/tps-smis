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
        Schema::create('course_works', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('course_id')->constrained(); 
            $table->foreignId('semester_id')->constrained(); // Link to semesters table 
            $table->string('coursework_title'); 
            $table->string('assessment_type'); // e.g., 'assignment', 'quiz', 'project' 
            $table->integer('max_score'); 
            $table->date('due_date')->nullable; 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_works');
    }
};
