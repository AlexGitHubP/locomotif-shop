<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_tracking', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->string('delay_time_days', 255);
            $table->text('comments', 255)->nullable();
            $table->enum('status', ['sentToShop', 'inTransition-sentToCarrier', 'inTransition-pickedUpByCarrier', 'inTransition-sentFromCarrier', 'delivered', 'canceled', 'delayed', 'contested']);
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
        Schema::dropIfExists('orders_tracking');
    }
}
