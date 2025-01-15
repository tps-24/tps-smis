<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('excuse_type')->nullable();
            $table->integer('rest_days')->nullable();
            $table->text('doctor_comments')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['excuse_type', 'rest_days', 'doctor_comments']);
        });
    }
    
};
