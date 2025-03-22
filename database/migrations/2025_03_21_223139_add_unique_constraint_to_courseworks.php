<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintToCourseworks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_works', function (Blueprint $table) {
            // Add composite unique constraint
            $table->unique(['course_id', 'assessment_type_id', 'coursework_title'], 'unique_course_assessment_title');
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
            // Drop the composite unique constraint
            $table->dropUnique('unique_course_assessment_title');
        });
    }
}
