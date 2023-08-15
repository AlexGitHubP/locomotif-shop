<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 255);
            $table->string('invoice_series', 255);
            $table->string('invoice_link', 255);
            $table->enum('status', ['fgo_sent','fgo_sentHalf','fgo_invoiced','fgo_invoicedHalf','fgo_storno','fgo_error']);
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
        Schema::dropIfExists('orders_invoices');
    }
}
