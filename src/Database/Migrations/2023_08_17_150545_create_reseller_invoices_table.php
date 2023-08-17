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
        Schema::create('reseller_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('designer_id')->index();
            $table->decimal('subtotal_sales', 10, 2)->default(0);
            $table->decimal('amount_to_invoice', 10, 2)->default(0);
            $table->decimal('amount_shown_to_shop', 10, 2)->default(0);
            $table->enum('invoice_status', ['notUploaded', 'uploaded', 'inProcesare', 'inregistrata', 'incasata']);
            $table->string('invoice', 255)->nullable();
            $table->unsignedBigInteger('nr_of_notice_sent')->default(0);
            $table->string('month', 255);
            $table->string('year', 255);
            $table->timestamps();

            $table->foreign('designer_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reseller_invoices');
    }
};
