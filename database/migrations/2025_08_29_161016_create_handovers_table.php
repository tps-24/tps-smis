<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('handovers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weapon_id')->constrained()->onDelete('cascade');
            $table->foreignId('staff_id')->constrained()->onDelete('cascade');
            $table->foreign('staff_id')
      ->references('id')
      ->on('staff')   
      ->onDelete('cascade');
            $table->dateTime('handover_date');
            $table->dateTime('return_date');
          
            $table->text('purpose');
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->enum('status', ['assigned', 'returned'])->default('assigned');


        });
    }

    public function down(): void {
        Schema::dropIfExists('handovers');
    }
};



