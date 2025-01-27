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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('forceNumber')->unique()->nullable();
            $table->string('rank');
            $table->string('nin')->nullable();
            $table->string('firstName');
            $table->string('middleName');
            $table->string('lastName');
            $table->string('gender');
            $table->date('DoB');
            $table->string('maritalStatus');
            $table->string('religion');
            $table->string('tribe');
            $table->string('phoneNumber');
            $table->string('email');
            $table->string('currentAddress');
            $table->string('permanentAddress');
            $table->unsignedInteger('department_id');
            $table->string('designation')->nullable();
            $table->string('educationLevel');
            $table->string('contractType');
            $table->date('joiningDate');
            $table->string('location');
            $table->string('nextofkinFullname')->nullable();
            $table->string('nextofkinRelationship')->nullable();
            $table->string('nextofkinPhoneNumber')->nullable();
            $table->string('nextofkinPhysicalAddress')->nullable();
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
