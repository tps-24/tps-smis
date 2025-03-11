
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
       

        // Create a new 'patients' table (if this is meant to be a fresh table)
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->unsignedBigInteger('company_id'); 
            $table->string('platoon'); 
            $table->foreignId('excuse_type_id')->constrained('excuse_types');
            $table->enum('status', ['pending', 'approved', 'rejected', 'treated'])->default('pending');
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->integer('rest_days');
            $table->text('doctor_comment')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Drop the newly created 'patients' table if needed
        Schema::dropIfExists('patients');
        
        
    }
};

