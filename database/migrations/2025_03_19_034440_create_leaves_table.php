<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('sir_major_id')->nullable()->constrained('staff')->onDelete('set null');
            $table->foreignId('inspector_id')->nullable()->constrained('staff')->onDelete('set null');
            $table->foreignId('chief_instructor_id')->nullable()->constrained('staff')->onDelete('set null');
            $table->string('leave_type');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('reason');
            $table->string('status')->default('pending'); // pending, forwarded_to_inspector, forwarded_to_chief, approved, rejected
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leaves');
    }
};
