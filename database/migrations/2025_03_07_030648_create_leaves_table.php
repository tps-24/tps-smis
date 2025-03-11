<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Run `php artisan make:migration create_leaves_table` to generate the migration file

public function up()
{
    Schema::create('leaves', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Links to users (students or staff)
        $table->enum('leave_type', ['sick', 'vacation', 'personal']); // Leave type
        $table->date('start_date');
        $table->date('end_date');
        $table->text('reason')->nullable();
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status of the leave
        $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // Stores Chief Instructor's ID
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
