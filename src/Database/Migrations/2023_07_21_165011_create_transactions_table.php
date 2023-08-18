<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('provider_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('transaction_identifier', 255);
            $table->text('comments', 255)->nullable();
            $table->enum('type', ['payment', 'invoice', 'cashOnDelivery', 'moneyTransfer']);
            $table->enum('status', ['transactionRecieved', 'transactionFirstHalfRecieved', 'paymentAwaitAdditionalInfos','paymentConfirmed','paymentCollected','paymentFirstHalfConfirmed', 'processing','requiresPaymentMethod','paymentFailed']);
            $table->tinyInteger('is_default');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('provider_id')->references('id')->on('transactions_providers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
