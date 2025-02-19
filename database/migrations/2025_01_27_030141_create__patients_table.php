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
        Schema::table('patients', function (Blueprint $table) {
            // Add student_id column
            $table->unsignedBigInteger('student_id')->after('id')->nullable();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

            // Drop name columns if they exist
            if (Schema::hasColumn('patients', 'first_name')) {
                $table->dropColumn('first_name');
            }
            if (Schema::hasColumn('patients', 'middle_name')) {
                $table->dropColumn('middle_name');
            }
            if (Schema::hasColumn('patients', 'last_name')) {
                $table->dropColumn('last_name');
            }
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
