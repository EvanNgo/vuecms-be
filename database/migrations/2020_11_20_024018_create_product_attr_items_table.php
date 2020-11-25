<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttrItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attr_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_attr_id');
            $table->string('name');
            $table->string('slug');
            $table->string('can_be_deleted')->default(true);

            $table->foreign('product_attr_id')->references('id')->on('product_attrs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attr_items');
    }
}
