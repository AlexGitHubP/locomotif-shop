<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarriersToTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carriers_to_type', function (Blueprint $table) {
            $table->unsignedBigInteger('carrier_id');
            $table->unsignedBigInteger('carrier_type_id');
            $table->dateTime          ('created_at');
            $table->dateTime          ('updated_at');

            $table->foreign('carrier_id')->references('id')->on('carriers')->onDelete('cascade');
            $table->foreign('carrier_type_id')->references('id')->on('carriers_type')->onDelete('cascade');

            $table->primary(['carrier_id', 'carrier_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carriers_to_type');
    }
}
