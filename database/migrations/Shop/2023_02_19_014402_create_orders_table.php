<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('customer', 255)->index();
            $table->string('phone', 255);
            $table->timestamp('created_at');
            $table->timestamp('completed_at');
            $table->unsignedBigInteger('user_id')->index();
            $table->string('type', 255);
            $table->string('status', 255);

            $table->foreign('user_id')->references('id')->on("users");

            $table->index(['customer', 'phone'], 'orders_customer_phone_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders', function(Blueprint $table) {
            $table->dropIndex('orders_customer_phone_index');
        });
    }
}
