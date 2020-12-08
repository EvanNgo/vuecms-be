<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('name');
            $table->string('brand');
            $table->integer('cost')->default(0);
            $table->integer('quatity')->default(0);
            $table->text('discription');
            $table->text('use');
            $table->integer('status')->default(0);
            $table->boolean('edit_flag')->default(true);
            $table->boolean('delete_flag')->default(false);
            $table->boolean('public_flag')->default(false);
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
        Schema::dropIfExists('products');
    }
}
