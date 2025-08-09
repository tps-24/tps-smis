<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('officers', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->string('officer_id')->unique(); // e.g., OFF001, TPC005
            $table->string('service_number')->unique(); // unique service number
            $table->string('full_name');
            $table->string('rank');
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Transferred'])->default('Active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('officers');
    }
};
