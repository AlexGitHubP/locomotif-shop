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
        Schema::create('orders_details', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->tinyInteger('is_billing');
            $table->tinyInteger('is_company');
            
            $table->string('name', 255);
            $table->string('surname', 255);
            $table->string('email', 255);
            $table->string('phone', 255);

            $table->string('street', 255);
            $table->string('nr', 255);
            $table->string('bloc', 255)->nullable();
            $table->string('scara', 255)->nullable();
            $table->string('apartament', 255)->nullable();
            $table->string('city', 255);
            $table->string('county', 255);
            $table->string('country', 255);
            $table->string('zip_code', 255)->nullable();
            $table->text('comments', 255)->nullable();

            $table->string('company_name', 255)->nullable();
            $table->string('company_type', 255)->nullable();
            $table->string('company_vat_type', 255)->nullable();
            $table->string('company_cui', 255)->nullable();
            $table->string('company_j', 255)->nullable();
            $table->string('company_nr', 255)->nullable();
            $table->string('company_series', 255)->nullable();
            $table->string('company_year', 255)->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_details');
    }
};
