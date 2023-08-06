<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('IBAN', 255)->nullable();
            $table->string('bank_name', 255)->nullable();
            $table->text('description', 255)->nullable();
            $table->enum('type',   ['online', 'offline']);
            $table->enum('status', ['hidden', 'published']);
            $table->tinyInteger('is_default')->length(1);
            $table->string('token', 255)->nullable();
            $table->integer('ordering')->length(10)->unsigned()->nullable();
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
        Schema::dropIfExists('transactions_providers');
    }
}
