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
            $table->decimal('shop_fee', 10, 2)->default(0);
            $table->enum('shop_fee_type', ['percent', 'fixed']);
            $table->boolean('maintenence');
            $table->string('succes_page', 255);
            $table->string('brand_logo', 255);
            $table->string('brand_name', 255);
            $table->string('brand_contact', 255);
            $table->string('brand_email',  255);
            $table->string('company_name', 255);
            $table->string('company_iban', 255);
            $table->string('company_bank', 255);
            $table->string('company_bank_swift', 255);
            $table->string('company_cui',  255);
            $table->string('company_address',  255);
            $table->string('company_registru_comert', 255);
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
