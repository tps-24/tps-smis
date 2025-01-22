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
        Schema::table('session_programmes', function (Blueprint $table) {
            $table->date('startDate')->after('year')->nullable();
            $table->date('endDate')->after('startDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('session_programmes', function (Blueprint $table) {
            $table->dropColumn('startDate');
            $table->dropColumn('endDate');
        });
    }
    
};





