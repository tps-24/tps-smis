<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weapon_models', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Example: AK-47, Glock 17, M4A1

            // Foreign key to weapon_types
            $table->unsignedBigInteger('weapon_type_id')->nullable();
            $table->foreign('weapon_type_id')
                ->references('id')
                ->on('weapon_types')
                ->onDelete('cascade'); // If a type is deleted, its models will also be deleted
                 $table->foreignId('category_id')
                  ->constrained('categories')
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weapon_models');
    }
};
