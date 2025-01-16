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
        Schema::create('semester_exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained(); 
            $table->foreignId('exam_id')->constrained('semester_exams'); 
            $table->integer('score');
            $table->foreignId('semester_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semester_exam_results');
    }
};
