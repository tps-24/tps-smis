<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Schema::table('patients', function (Blueprint $table) {
        //     // Add student_id column
        //     $table->unsignedBigInteger('student_id')->after('id')->nullable();
        //     $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

        //     // Drop name columns if they exist
        //     if (Schema::hasColumn('patients', 'first_name')) {
        //         $table->dropColumn('first_name');
        //     }
        //     if (Schema::hasColumn('patients', 'middle_name')) {
        //         $table->dropColumn('middle_name');
        //     }
        //     if (Schema::hasColumn('patients', 'last_name')) {
        //         $table->dropColumn('last_name');
        //     }
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('excuse_type_id')->constrained('excuse_types');
            $table->enum('status', ['pending', 'approved', 'rejected', 'treated'])->default('pending');
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->text('receptionist_comment')->nullable();
            $table->integer('rest_days');
            $table->text('doctor_comment')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
         
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

