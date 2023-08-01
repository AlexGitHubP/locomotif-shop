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
        Schema::create('shop_settings', function (Blueprint $table) {
            $table->id();
            $table->string('tva', 255);
            $table->enum('tax_type', ['percent', 'fixed']);
            $table->boolean('maintenence');
            $table->string('succes_page', 255);
            $table->string('brand_logo', 255);
            $table->string('brand_name', 255);
            $table->string('brand_contact', 255);
            $table->string('brand_email', 255);
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
        Schema::dropIfExists('shop_settings');
    }
};
