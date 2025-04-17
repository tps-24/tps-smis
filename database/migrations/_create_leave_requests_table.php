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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->unsignedBigInteger('company_id'); 
            $table->string('platoon');
            $table->string('location');
            $table->text('reason');
            $table->text('attachments')->nullable(); // optional
            $table->enum('status', ['pending', 'approved', 'rejected', 'Ready'])->default('pending');
            $table->dropColumn('status'); // Drop the old ENUM
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();   
            $table->string('phone_number')->nullable(); 
            
            $table->timestamps();
        });

        Schema::table('leave_requests', function (Blueprint $table) {
            $table->enum('status', [
                'pending', 
                'forwarded_to_chief_instructor', 
                'approved', 
                'rejected', 
                'ready'
            ])->default('pending');
        });
    }

    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected', 'Ready'])->default('pending');
        });
    }
};
