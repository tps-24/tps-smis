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
            $table->foreignId('programme_id')->constrained(); //Link to Programme table
            $table->foreignId('course_id')->constrained(); 
            $table->foreignId('semester_id')->constrained(); // Link to semesters table 
            $table->string('assessment_type_id')->constrained('assessment_types'); // Link to coursework types; // e.g., 'assignment', 'quiz', 'project' 
            $table->string('coursework_title'); //assignment one, assignment two , quiz 1, quiz 3, etc
            $table->integer('max_score'); 
            $table->date('due_date')->nullable; 
            $table->foreignId('session_programme_id')->constrained('session_programmes'); //Link to Session Programme table
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
        Schema::dropIfExists('course_works');
    }
};
