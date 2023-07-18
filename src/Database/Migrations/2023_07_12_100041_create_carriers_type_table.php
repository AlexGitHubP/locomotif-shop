<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarriersTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carriers_type', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('description', 255);
            $table->string('delivery_time_days', 255);
            $table->string('price', 255);
            $table->enum('price_type', ['fixed', 'percent']);
            $table->enum('status', ['hidden', 'published']);
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
        Schema::dropIfExists('carriers_type');
    }
}
