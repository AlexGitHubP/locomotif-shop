<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarriersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carriers', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('name', 255);
            $table->text('description', 255);
            $table->string('email', 255);
            $table->string('phone', 255);
            $table->string('site_link', 255);
            $table->string('logo', 255);
            $table->string('token', 255);
            $table->integer('ordering')->length(10)->unsigned()->nullable();
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
        Schema::dropIfExists('carriers');
    }
}
