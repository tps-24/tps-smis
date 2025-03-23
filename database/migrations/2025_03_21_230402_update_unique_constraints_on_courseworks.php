<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUniqueConstraintsOnCourseworks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_works', function (Blueprint $table) {
            // Drop existing unique constraints
            $table->dropUnique('unique_assessment_coursework'); // Drop old constraint
            $table->dropUnique('unique_assessment_coursework_course'); // Drop additional constraint

            // Add the updated unique constraint
            $table->unique(['course_id', 'assessment_type_id', 'coursework_title'], 'updated_unique_course_assessment_title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_works', function (Blueprint $table) {
            // Drop the updated unique constraint
            $table->dropUnique('updated_unique_course_assessment_title');

            // Re-add the original constraints
            $table->unique(['assessment_type_id', 'coursework_title'], 'unique_assessment_coursework');
            $table->unique(['course_id', 'assessment_type_id', 'coursework_title'], 'unique_assessment_coursework_course');
        });
    }
}

