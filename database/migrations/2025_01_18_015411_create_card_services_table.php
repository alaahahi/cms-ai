<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('card_services')) {
            return;
        }

        Schema::create('card_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('card_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('service_name_ar')->nullable();
            $table->string('service_name_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('specialty_ar')->nullable();
            $table->string('specialty_en')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->string('currency', 10)->nullable();
            $table->string('working_days')->nullable();
            $table->string('working_hours')->nullable();
            $table->integer('appointments_per_day')->default(5);
            $table->date('expir_date')->nullable();
            $table->boolean('show_on_app')->default(true);
            $table->boolean('is_popular')->default(false);
            $table->integer('review_rate')->default(5);
            $table->integer('ex_year')->default(0);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('card_services');
    }
};
