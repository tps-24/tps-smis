<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('handovers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weapon_id')->constrained()->onDelete('cascade');
            //$table->foreignId('staff_id')->constrained()->onDelete('cascade');
            $table->foreign('staff_id')
      ->references('id')
      ->on('staffs')   // ✅ make sure matches actual table name
      ->onDelete('cascade');
            $table->dateTime('handover_date');
            $table->dateTime('return_date');
           //  $table->dateTime('actual_return_date')->nullable(); 
            $table->text('purpose');
            $table->text('remarks')->nullable();
            $table->timestamps();
               // $table->enum('status', ['active', 'returned'])->default('active')->after('actual_return_date');
            $table->enum('status', ['assigned', 'returned'])->default('assigned');


        });
    }

    public function down(): void {
        Schema::dropIfExists('handovers');
    }
};



