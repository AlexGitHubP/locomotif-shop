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
            $table->string('invoice_reference');
            $table->enum('type', ['proforma', 'fiscala']);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('currency');
            $table->enum('status', ['fgo_inserted', 'fgo_sent', 'fgo_rejected', 'fgo_error']);
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
