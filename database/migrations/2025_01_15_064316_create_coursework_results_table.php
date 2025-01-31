<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Holds individual coursework scores for students.
    public function up(): void
    {
        Schema::create('coursework_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained(); 
            $table->foreignId('course_id')->constrained('courses'); 
            $table->foreignId('coursework_id')->constrained('course_works'); 
            $table->integer('score');
            $table->foreignId('semester_id')->constrained();
            $table->unsignedBigInteger('created_by')->constrained('users');
            $table->unsignedBigInteger('updated_by')->constrained('users')->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable(false);
            $table->timestamp('updated_at')->nullable(true)->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coursework_results');
    }
};
