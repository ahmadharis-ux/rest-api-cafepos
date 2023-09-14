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
        Schema::create('beverages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('beverage_category_id')->unsigned();
            $table->foreign('beverage_category_id')->references('id')->on('beverages_categories');
            $table->foreignId('recipe_id')->unsigned();
            $table->foreign('recipe_id')->references('id')->on('recipes');
            $table->unsignedBigInteger('price');
            $table->integer('netto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beverages');
    }
};
