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
        Schema::create('weapon_handovers', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('weapon_id')
                ->constrained('weapons') 
                ->onDelete('cascade');
            
            $table->unsignedBigInteger('staff_id'); 
            $table->foreign('staff_id')
                ->references('id')
                ->on('staff') 
                ->onDelete('cascade');

            $table->enum('status', ['assigned', 'returned'])->default('assigned');
            $table->text('remarks')->nullable();
            $table->timestamp('handover_date');
            $table->timestamp('return_date')->nullable(); 
            $table->text('purpose')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weapon_handovers');
    }
};
