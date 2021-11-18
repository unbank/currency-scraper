<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCryptoCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('from');  // ticker sysmbol
            $table->string('to');   // ticker symbol
            $table->decimal('rate', 18,9);
            $table->decimal('volume', 18,9);
            $table->json('source_data');
            $table->string('source')->nullable();
            $table->string('currency_type');
            $table->string('transaction_type')->default('sell');
            $table->decimal('markup')->nullable();
            $table->timestamps();

            $table->index(['from', 'to', 'source']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
