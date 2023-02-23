<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->integer('count');
            $table->float('discount', 8, 2);
            $table->float('cost', 8, 2);

            $table->foreign('order_id')->references('id')->on("orders")->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on("products");

            $table->unique(['order_id', 'product_id'], 'fk_order_items_order_product');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items', function (Blueprint $table) {
            $table->dropIndex('order_id');
            $table->dropIndex('product_id');
        });
    }
}
