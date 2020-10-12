<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipeItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipe_items', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->bigInteger("recipe_id");
            $table->bigInteger('product_id');
            $table->string("product_name");
            $table->double("quantity");
            $table->string("uom");
            $table->string('product_image');
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
        Schema::dropIfExists('recipe_items');
    }
}
