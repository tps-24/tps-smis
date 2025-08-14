<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('weapon_movements', function (Blueprint $table) {
            $table->id();
            $table->string('movement_id')->unique(); // e.g., MVT001
            $table->unsignedBigInteger('weapon_id');
            $table->enum('movement_type', ['Issue', 'Return', 'Transfer', 'Maintenance Out', 'Maintenance In']);
            $table->string('purpose'); // e.g., 'Guard Duty', 'Training'
            $table->dateTime('issue_date_time');
            $table->dateTime('return_date_time')->nullable();

            // Officer relationships
            $table->unsignedBigInteger('issued_by_officer_id');
            $table->unsignedBigInteger('issued_to_officer_id');
            $table->unsignedBigInteger('returned_by_officer_id')->nullable();

            $table->string('return_condition')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('weapon_id')->references('id')->on('weapons')->onDelete('cascade');
            $table->foreign('issued_by_officer_id')->references('id')->on('officers')->onDelete('cascade');
            $table->foreign('issued_to_officer_id')->references('id')->on('officers')->onDelete('cascade');
            $table->foreign('returned_by_officer_id')->references('id')->on('officers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weapon_movement');
    }
};
