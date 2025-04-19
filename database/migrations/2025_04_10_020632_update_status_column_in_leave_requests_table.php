<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('leave_requests', function (Blueprint $table) {
        $table->string('status', 255)->change(); // Set length to 255 or more
    });
}

public function down()
{
    Schema::table('leave_requests', function (Blueprint $table) {
        $table->string('status', 100)->change(); // Or whatever the original length was
    });
}

};
