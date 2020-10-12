<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('store_id');
            $table->bigInteger('recipe_id');
            $table->bigInteger('meal_id');
            $table->string('meal_name');
            $table->string('meat_type');
            $table->longText('meal_description');
            $table->string('meal_serving');
            $table->string('meal_preparation');
            $table->double('estimated_amount');
            $table->longText('meal_instruction');
            $table->longtext('meal_nutrition');
            $table->string('meal_image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meals');
    }
}
