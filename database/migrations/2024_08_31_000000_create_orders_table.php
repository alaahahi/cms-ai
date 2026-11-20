<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('orders')) {
            return;
        }

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('order_id')->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->string('currency', 10)->nullable();
            $table->string('status')->default('pending');
            $table->string('state')->default('initial');
            $table->string('link')->nullable();
            $table->string('token')->nullable();
            $table->string('transactionId')->nullable();
            $table->string('3DSecureId')->nullable();
            $table->string('card_number')->nullable();
            $table->string('salse')->nullable();
            $table->string('name')->nullable();
            $table->string('family_name')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
