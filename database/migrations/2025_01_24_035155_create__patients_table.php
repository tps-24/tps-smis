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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('excuse_type_id')->constrained('excuse_types');
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable(); // Optional
            $table->string('last_name')->nullable();
            $table->integer('rest_days');
            $table->text('doctor_comment')->nullable();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
