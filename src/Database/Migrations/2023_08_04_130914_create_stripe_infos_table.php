<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userID')->index();
            $table->string('stripeAccountID', 225)->nullable();
            $table->string('stripeCustomerID', 225)->nullable();
            $table->string('stripeIntentID', 225)->nullable();
            $table->string('stripePayID', 225)->nullable();
            $table->enum('type', ['customer', 'connect'])->default('customer');
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
        Schema::dropIfExists('stripe_infos');
    }
};
