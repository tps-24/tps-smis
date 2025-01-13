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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('force_number')->unique()->nullable();
            $table->string('rank')->default("Recruit");
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->char('gender');
            $table->char('blood_group')->nullable();
            $table->string('phone')->nullable();
            $table->string('nin')->unique();
            $table->string('dob');
            $table->string('education_level');
            $table->string('home_region');
            $table->string('company')->nullable();
            $table->string('photo')->nullable();
            $table->double('height')->nullable();
            $table->double('weight')->nullable();
            $table->char('platoon')->nullable();
            $table->string('next_kin_names')->nullable();
            $table->string('next_kin_phone')->nullable();
            $table->string('next_kin_relationship')->nullable();
            $table->string('next_kin_address')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onupdate('update')->ondelete('null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
