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
            $table->string('delay_time_days', 255);
            $table->text('comments', 255);
            $table->enum('status', ['placata-de-la-sediu', 'preluata-de-curier', 'in-drum-spre-client', 'livrata', 'in-intarziere']);
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
