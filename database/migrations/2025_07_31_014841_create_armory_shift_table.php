<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('armory_shift', function (Blueprint $table) {
            $table->id();
            $table->string('shift_id')->unique(); // e.g., SHF001
            $table->date('shift_date');
            $table->time('shift_start_time');
            $table->time('shift_end_time');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('armory_shift');
    }
};
