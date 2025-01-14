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
        Schema::create('attendences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('platoon_id');
            $table->integer('present');
            $table->integer('sentry')->nullable();
            $table->integer('absent')->nullable();
            $table->integer('excuse_duty')->nullable();
            $table->integer('kazini')->nullable();
            $table->integer('adm')->nullable();
            $table->integer('safari')->nullable();
            $table->integer('mess')->nullable();
            $table->integer('sick')->nullable();
            $table->integer('off')->nullable();
            $table->integer('female');
            $table->integer('male');
            $table->integer('total');
            $table->timestamps();

            $table->foreign('platoon_id')->references('id')->on('platoons')->onupdate('update')->ondelete('null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendences');
    }
};
