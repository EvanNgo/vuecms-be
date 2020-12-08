<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillSubItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_sub_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bill_item_id');
            $table->unsignedBigInteger('product_item_id');
            $table->string('name');
            $table->integer('cost');
            $table->integer('quatity');
            $table->integer('total_cost');

            $table->foreign('bill_item_id')->references('id')->on('bill_items')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_sub_items');
    }
}
