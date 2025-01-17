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
        Schema::create('card_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('card_id'); 
            $table->string('service_name'); 
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2)->nullable(); 
            $table->timestamps();

            // الأعمدة الجديدة
            $table->json('working_days')->nullable(); // أيام الدوام مثل ["Saturday", "Sunday", "Monday"]
            $table->json('working_hours')->nullable(); // ساعات الدوام مثل {"start": "09:00", "end": "17:00"}
            $table->integer('appointments_per_day')->default(5); // الحد الأقصى للمواعيد في اليوم

            // ربط المفتاح الخارجي
            $table->foreign('card_id')->references('id')->on('card')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_services');
    }
};
