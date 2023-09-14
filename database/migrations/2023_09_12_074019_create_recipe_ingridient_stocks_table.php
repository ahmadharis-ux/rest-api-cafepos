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
        Schema::create('recipe_ingridient_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->unsigned();
            $table->foreign('recipe_id')->references('id')->on('recipes');
            $table->foreignId('ingredient_stock_id')->unsigned();
            $table->foreign('ingredient_stock_id')->references('id')->on('ingredient_stocks');
            $table->integer('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_ingridient_stocks');
    }
};
