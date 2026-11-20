<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('categories')) {
            return;
        }

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('card_id')->nullable();
            $table->string('name_en')->nullable();
            $table->string('name_ar')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->decimal('discount', 8, 2)->default(0);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
