
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('order_id');  // UUID for the order ID
            $table->decimal('amount', 10, 2);  // Amount (adjust precision if necessary)
            $table->string('currency', 3);  // Currency code, e.g., IQD
            $table->string('status')->default('pending');  // Status of the payment
            $table->string('state')->default('initial');  // State of the order
            $table->timestamps();  // Created at and Updated at fields
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
